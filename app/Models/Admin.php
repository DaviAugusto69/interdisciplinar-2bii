<?php
namespace App\Models;

class Admin
{
    // Busca um administrador pelo e-mail
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connect()->prepare(
            'SELECT * FROM admins WHERE email = ?'
        );

        $stmt->execute([$email]);

        return $stmt->fetch() ?: null;
    }

    // Cadastra um novo administrador
    public static function create(
        string $name,
        string $email,
        string $password
    ): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = Database::connect()->prepare(
            'INSERT INTO admins (name, email, password)
             VALUES (?, ?, ?)'
        );

        $stmt->execute([$name, $email, $hash]);

        return (int) Database::connect()->lastInsertId();
    }
}
