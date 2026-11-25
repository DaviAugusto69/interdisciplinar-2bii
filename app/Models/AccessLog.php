<?php

namespace App\Models;

class AccessLog
{
    public static function countAll(): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT COUNT(*) FROM access_logs');
        return (int) $stmt->fetchColumn();
    }

    public static function recent(): array
    {
        return Database::connect()->query(
            'SELECT user_id, accessed_at FROM access_logs ORDER BY id DESC LIMIT 10'
        )->fetchAll();
    }

    public static function record(?int $userId = null): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('INSERT INTO access_logs (user_id) VALUES (:user_id)');
        $stmt->execute([':user_id' => $userId]);
    }
}