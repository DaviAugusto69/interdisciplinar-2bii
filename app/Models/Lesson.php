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
        $stmt = $pdo->query('SELECT slug, title, icon FROM lessons ORDER BY id ASC');
        return $stmt->fetchAll(\PDO::FETCH_NUM);
    }

    /**
     * Retorna todos os campos para o painel administrativo
     */
    public static function allAdmin(): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT * FROM lessons ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM lessons WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function count(): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT COUNT(*) FROM lessons');
        return (int) $stmt->fetchColumn();
    }

    public static function create(array $data): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare(
            'INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text)
             VALUES (:slug, :title, :description, :type, :icon, :media_url, :body_text)'
        );
        $stmt->execute([
            ':slug'        => $data['slug'],
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':type'        => $data['type'],
            ':icon'        => $data['icon'],
            ':media_url'   => $data['media_url'],
            ':body_text'   => $data['body_text'],
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
                 body_text = :body_text
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
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('DELETE FROM lessons WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}