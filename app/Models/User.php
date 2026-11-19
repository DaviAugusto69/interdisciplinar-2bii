<?php
namespace App\Models;

// Database pertence ao mesmo namespace App\Models.

class User 
{
    public static function all(): array
    {
        return Database::connect()
            ->query('SELECT * FROM users ORDER BY name')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id, name, email FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function emailExists(string $email): bool
    {
        $stmt = Database::connect()->prepare('SELECT 1 FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }

    public static function create(string $name, string $email): int
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO users (name, email) VALUES (?, ?)'
        );
        $stmt->execute([$name, $email]);
        return (int) Database::connect()->lastInsertId();
    }

    public static function count(): int
    {
        return (int) Database::connect()
            ->query('SELECT COUNT(*) FROM users')
            ->fetchColumn();
    }

    /** Dados do perfil (inclui telefone e se já existe senha). */
    public static function profile(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT id, name, email, phone, (password_hash IS NOT NULL) AS has_password
             FROM users WHERE id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $row['has_password'] = (bool) $row['has_password'];
        return $row;
    }

    public static function emailTakenByOther(string $email, int $id): bool
    {
        $stmt = Database::connect()->prepare(
            'SELECT 1 FROM users WHERE email = ? AND id <> ?'
        );
        $stmt->execute([$email, $id]);
        return (bool) $stmt->fetchColumn();
    }

    public static function updateProfile(int $id, string $name, string $email, string $phone): void
    {
        $stmt = Database::connect()->prepare(
            'UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?'
        );
        $stmt->execute([$name, $email, $phone === '' ? null : $phone, $id]);
    }

    public static function passwordHash(int $id): ?string
    {
        $stmt = Database::connect()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $hash = $stmt->fetchColumn();
        return is_string($hash) ? $hash : null;
    }

    public static function setPassword(int $id, string $hash): void
    {
        $stmt = Database::connect()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$hash, $id]);
    }
}
