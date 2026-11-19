<?php
namespace App\Models;

class SupportMessage
{
    /** Assuntos aceitos (chave gravada no banco => texto mostrado). */
    public const SUBJECTS = [
        'duvida'   => 'Dúvida sobre o site',
        'problema' => 'Algo não está funcionando',
        'conta'    => 'Ajuda com minha conta ou senha',
        'sugestao' => 'Sugestão',
        'outro'    => 'Outro assunto',
    ];

    public static function create(string $name, string $email, string $subject, string $message): int
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO support_messages (name, email, subject, message) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $subject, $message]);
        return (int) Database::connect()->lastInsertId();
    }
}