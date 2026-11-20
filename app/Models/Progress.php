<?php

namespace App\Models;

class Progress
{
    public static function countCompleted(): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT COUNT(*) FROM user_progress');
        return (int) $stmt->fetchColumn();
    }

    public static function markCompleted(int $userId, string $lessonSlug): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO user_progress (user_id, lesson_slug) VALUES (:user_id, :lesson_slug)'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':lesson_slug' => $lessonSlug,
        ]);
    }
}