<?php

namespace App\Models;

class Lesson
{
    /**
     * Mantém compatibilidade com PagesController::aprendizagem()
     * Retorna array indexado: [slug, title, icon]
     */
    public static function all(): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT l.slug, l.title, l.icon, l.description, l.type, l.media_url, l.body_text, c.name
             FROM lessons l LEFT JOIN categories c ON c.id = l.category_id
             WHERE l.is_published = 1 AND l.archived_at IS NULL ORDER BY l.id ASC');
        return $stmt->fetchAll(\PDO::FETCH_NUM);
    }

    /**
     * Retorna todos os campos para o painel administrativo
     */
    public static function recentPublications(): array
    {
        return Database::connect()->query(
            'SELECT slug, title FROM lessons
             WHERE is_published = 1 AND archived_at IS NULL
             ORDER BY published_at DESC, id DESC LIMIT 5'
        )->fetchAll();
    }

    public static function mediaUrl(string $value): ?string
    {
        if (preg_match('~^assets/(?:[\w-]+/)*[\w .-]+\.(?:mp4|webm|mp3|ogg|wav|png|jpe?g|webp|gif)$~iD', $value)) {
            return asset($value);
        }

        if (filter_var($value, FILTER_VALIDATE_URL)
            && in_array(strtolower(parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true)) {
            return $value;
        }
        return null;
    }

    public static function allAdmin(): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT * FROM lessons WHERE archived_at IS NULL ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM lessons WHERE id = :id AND archived_at IS NULL');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function count(): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT COUNT(*) FROM lessons WHERE archived_at IS NULL');
        return (int) $stmt->fetchColumn();
    }

    public static function create(array $data): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare(
            'INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text, is_published, category_id, published_at)
             VALUES (:slug, :title, :description, :type, :icon, :media_url, :body_text, :is_published, :category_id, IF(:publish_now = 1, CURRENT_TIMESTAMP, NULL))'
        );
        $stmt->execute([
            ':slug'        => $data['slug'],
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':type'        => $data['type'],
            ':icon'        => $data['icon'],
            ':media_url'   => $data['media_url'],
            ':body_text'   => $data['body_text'],
            ':is_published' => $data['is_published'],
            ':category_id' => $data['category_id'],
            ':publish_now' => $data['is_published'],
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare(
            'UPDATE lessons
             SET title = :title,
                 description = :description,
                 type = :type,
                 icon = :icon,
                 media_url = :media_url,
                 body_text = :body_text,
                 published_at = IF(:publish_now = 1 AND is_published = 0, CURRENT_TIMESTAMP, published_at),
                 is_published = :is_published,
                 category_id = :category_id
             WHERE id = :id'
        );
        $stmt->execute([
            ':id'          => $id,
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':type'        => $data['type'],
            ':icon'        => $data['icon'],
            ':media_url'   => $data['media_url'],
            ':body_text'   => $data['body_text'],
            ':is_published' => $data['is_published'],
            ':category_id' => $data['category_id'],
            ':publish_now' => $data['is_published'],
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('UPDATE lessons SET archived_at = CURRENT_TIMESTAMP, is_published = 0 WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}