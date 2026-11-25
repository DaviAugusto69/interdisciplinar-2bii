<?php

namespace App\Models;

class Progress
{
    public static function countCompleted(): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT COUNT(*) FROM activity_results WHERE completed_at IS NOT NULL');
        return (int) $stmt->fetchColumn();
    }

    public static function topLessons(): array
    {
        return Database::connect()->query(
            'SELECT l.title, COUNT(up.user_id) AS total
             FROM lessons l LEFT JOIN user_progress up ON up.lesson_slug = l.slug
             GROUP BY l.id, l.title ORDER BY total DESC, l.id ASC LIMIT 10'
        )->fetchAll();
    }

    public static function completedLessons(int $userId): array
    {
        $stmt = Database::connect()->prepare(
            'SELECT p.lesson_slug FROM user_progress p JOIN lessons l ON l.slug = p.lesson_slug
             WHERE p.user_id = ? AND l.is_published = 1 AND l.archived_at IS NULL'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function recentForUser(int $userId): array
    {
        $stmt = Database::connect()->prepare(
            "SELECT title, completed_at, kind FROM (
                SELECT l.title, p.completed_at, 'lesson' AS kind
                FROM user_progress p JOIN lessons l ON l.slug = p.lesson_slug
                WHERE p.user_id = ? AND l.archived_at IS NULL AND l.is_published = 1
                UNION ALL
                SELECT a.title, r.completed_at, 'activity' AS kind
                FROM activity_results r JOIN activities a ON a.id = r.activity_id
                JOIN lessons l ON l.id = a.lesson_id
                WHERE r.user_id = ? AND r.completed_at IS NOT NULL
                  AND l.archived_at IS NULL AND l.is_published = 1 AND a.is_active = 1
             ) AS history ORDER BY completed_at DESC, title LIMIT 5"
        );
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }

    public static function markCompleted(int $userId, string $lessonSlug): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare(
            'INSERT INTO user_progress (user_id, lesson_slug)
             SELECT u.id, l.slug FROM users u CROSS JOIN lessons l
             WHERE u.id = :user_id AND u.archived_at IS NULL AND l.slug = :lesson_slug AND l.is_published = 1 AND l.archived_at IS NULL
             ON DUPLICATE KEY UPDATE completed_at = user_progress.completed_at'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':lesson_slug' => $lessonSlug,
        ]);
    }
}
