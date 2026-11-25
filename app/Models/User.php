<?php
namespace App\Models;

// Database pertence ao mesmo namespace App\Models.

class User 
{
    public static function all(): array
    {
        return Database::connect()
            ->query("SELECT id, CONCAT_WS(' ', name, NULLIF(last_name, '')) AS name, email
                     FROM users WHERE archived_at IS NULL ORDER BY name")
            ->fetchAll();
    }

    public static function allAdmin(string $search = ''): array
    {
        $pdo = Database::connect();

        $sql = "SELECT u.id, CONCAT_WS(' ', u.name, NULLIF(u.last_name, '')) AS name, u.email,
                       (SELECT COUNT(*) FROM user_progress up WHERE up.user_id = u.id) AS completed_count,
                       (SELECT COUNT(*) FROM activity_results ar WHERE ar.user_id = u.id AND ar.completed_at IS NOT NULL) AS activities_completed,
                       (SELECT COALESCE(SUM(ar.points_awarded), 0) FROM activity_results ar WHERE ar.user_id = u.id) AS points,
                       (SELECT COUNT(*) FROM access_logs al WHERE al.user_id = u.id) AS access_count
                FROM users u WHERE u.archived_at IS NULL";

        if ($search !== '') {
            $sql .= " AND (CONCAT_WS(' ', u.name, NULLIF(u.last_name, '')) LIKE ? OR u.email LIKE ?)";
        }

        $sql .= ' ORDER BY u.id DESC';

        $stmt = $pdo->prepare($sql);

        if ($search !== '') {
            $term = '%' . $search . '%';
            $stmt->execute([$term, $term]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connect();
        $pdo->prepare('UPDATE users SET archived_at = CURRENT_TIMESTAMP WHERE id = ? AND archived_at IS NULL')->execute([$id]);
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            "SELECT id, CONCAT_WS(' ', name, NULLIF(last_name, '')) AS name, email
             FROM users WHERE id = ? AND archived_at IS NULL"
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

    public static function create(string $name, string $email, ?string $passwordHash = null): int
    {
        $pdo = Database::connect();
        if ($passwordHash === null) {
            $stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
            $stmt->execute([$name, $email]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $passwordHash]);
        }
        return (int) $pdo->lastInsertId();
    }

    public static function register(string $name, string $email, string $passwordHash, string $cpf, string $address): int
    {
        $pdo = Database::connect();
        $parts = preg_split('/\s+/', $name, 2);
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, last_name, email, cpf, address, password_hash) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$parts[0], $parts[1] ?? '', $email, $cpf, $address, $passwordHash]);
        return (int) $pdo->lastInsertId();
    }

    public static function count(): int
    {
        return (int) Database::connect()
            ->query('SELECT COUNT(*) FROM users WHERE archived_at IS NULL')
            ->fetchColumn();
    }

    /** Dados do perfil (inclui telefone e se já existe senha). */
    public static function profile(int $id): ?array
    {
        $stmt = Database::connect()->prepare(
            "SELECT id, CONCAT_WS(' ', name, NULLIF(last_name, '')) AS name, email, phone, address,
                    (password_hash IS NOT NULL) AS has_password
             FROM users WHERE id = ? AND archived_at IS NULL"
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

    public static function updateProfile(int $id, string $name, string $email, string $phone, string $address): void
    {
        $stmt = Database::connect()->prepare(
            'UPDATE users SET name = ?, last_name = ?, email = ?, phone = ?, address = ? WHERE id = ? AND archived_at IS NULL'
        );
        $parts = preg_split('/\s+/', $name, 2);
        $stmt->execute([$parts[0], $parts[1] ?? '', $email, $phone === '' ? null : $phone, $address, $id]);
    }

    public static function credentials(string $email): ?array
    {
        $stmt = Database::connect()->prepare('SELECT id, password_hash FROM users WHERE email = ? AND archived_at IS NULL');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function preferences(int $id): ?array
    {
        $stmt = Database::connect()->prepare('SELECT font_size AS fonte, color_theme AS tema FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function savePreferences(int $id, string $font, string $theme): void
    {
        $stmt = Database::connect()->prepare('UPDATE users SET font_size = ?, color_theme = ? WHERE id = ?');
        $stmt->execute([$font, $theme, $id]);
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
