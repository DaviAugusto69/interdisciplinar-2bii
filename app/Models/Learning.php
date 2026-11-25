<?php
namespace App\Models;

class Learning
{
    private const CATALOG = [
        'categories' => ['name'],
        'activities' => ['lesson_id', 'title', 'prompt', 'choices', 'correct_answer', 'points', 'is_active'],
        'rewards' => ['name', 'description', 'required_points', 'is_active'],
    ];

    public static function catalog(string $table): array
    {
        if (!isset(self::CATALOG[$table])) {
            throw new \InvalidArgumentException('Catálogo inválido');
        }
        return Database::connect()->query("SELECT * FROM ${table} ORDER BY id")->fetchAll();
    }

    public static function save(string $table, int $id, array $data): void
    {
        $fields = self::CATALOG[$table];
        $values = array_map(static fn ($field) => $data[$field], $fields);
        $assignments = implode(', ', array_map(static fn ($field) => "$field = ?", $fields));
        $pdo = Database::connect();
        if ($id > 0) {
            $pdo->prepare("UPDATE $table SET $assignments WHERE id = ?")->execute([...$values, $id]);
        } else {
            $columns = implode(', ', $fields);
            $parameters = implode(', ', array_fill(0, count($fields), '?'));
            $pdo->prepare("INSERT INTO $table ($columns) VALUES ($parameters)")->execute($values);
        }
    }

    public static function activitiesForLesson(string $slug, int $userId): array
    {
        $stmt = Database::connect()->prepare(
            'SELECT a.id, a.title, a.prompt, a.choices, a.points, (a.correct_answer IS NOT NULL) AS requires_answer,
                    r.completed_at
             FROM activities a JOIN lessons l ON l.id = a.lesson_id
             LEFT JOIN activity_results r ON r.activity_id = a.id AND r.user_id = ?
             WHERE l.slug = ? AND a.is_active = 1 AND l.is_published = 1 AND l.archived_at IS NULL
             ORDER BY a.id'
        );
        $stmt->execute([$userId, $slug]);
        return $stmt->fetchAll();
    }

    public static function answer(int $userId, int $activityId, string $slug, string $answer): bool
    {
        $pdo = Database::connect();
        $pdo->beginTransaction();
        try {
            // Serialize point awards for this user, including simultaneous activity submissions.
            $user = $pdo->prepare('SELECT id FROM users WHERE id = ? AND archived_at IS NULL FOR UPDATE');
            $user->execute([$userId]);
            $stmt = $pdo->prepare(
                'SELECT a.correct_answer, a.choices, a.points FROM activities a JOIN lessons l ON l.id = a.lesson_id
                 WHERE a.id = ? AND l.slug = ? AND a.is_active = 1
                   AND l.is_published = 1 AND l.archived_at IS NULL FOR UPDATE'
            );
            $stmt->execute([$activityId, $slug]);
            $activity = $stmt->fetch();
            if (!$user->fetchColumn() || !$activity) {
                $pdo->rollBack();
                return false;
            }

            $correct = $activity['correct_answer'] === null
                || mb_strtolower($answer) === mb_strtolower($activity['correct_answer']);
            if ($activity['choices'] !== null && !in_array($answer, explode("\n", $activity['choices']), true)) {
                $correct = false;
            }
            $stmt = $pdo->prepare(
                'INSERT INTO activity_results (user_id, activity_id, points_awarded, completed_at)
                 VALUES (?, ?, ?, IF(? = 1, CURRENT_TIMESTAMP, NULL))
                 ON DUPLICATE KEY UPDATE
                    attempts = attempts + IF(completed_at IS NULL, 1, 0),
                    points_awarded = COALESCE(points_awarded, VALUES(points_awarded)),
                    completed_at = COALESCE(completed_at, VALUES(completed_at))'
            );
            $stmt->execute([$userId, $activityId, $correct ? $activity['points'] : null, (int) $correct]);

            if ($correct) {
                $stmt = $pdo->prepare(
                    'INSERT INTO user_rewards (user_id, reward_id)
                     SELECT ?, id FROM rewards WHERE is_active = 1 AND required_points <=
                         (SELECT COALESCE(SUM(points_awarded), 0) FROM activity_results WHERE user_id = ?)
                     ON DUPLICATE KEY UPDATE awarded_at = user_rewards.awarded_at'
                );
                $stmt->execute([$userId, $userId]);
            }
            $pdo->commit();
            return $correct;
        } catch (\Throwable $error) {
            $pdo->rollBack();
            throw $error;
        }
    }

    public static function rewardsForUser(int $userId): array
    {
        $stmt = Database::connect()->prepare(
            'SELECT r.name, r.description, ur.awarded_at FROM user_rewards ur
             JOIN rewards r ON r.id = ur.reward_id WHERE ur.user_id = ? ORDER BY ur.awarded_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}

