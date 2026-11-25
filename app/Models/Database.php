<?php
namespace App\Models;
use PDO;
use PDOException;
class Database
{
    private static ?PDO $pdo = null;

    public static function connect(): PDO
    {
        if (self::$pdo !== null)
            return self::$pdo;

        $local = __DIR__ . '/../../config/database.local.php';
        $db = require (is_file($local) ? $local : __DIR__ . '/../../config/database.php');
        foreach (['host' => 'HOST', 'port' => 'PORT', 'name' => 'NAME', 'user' => 'USER', 'pass' => 'PASSWORD'] as $key => $suffix) {
            $value = getenv('DB_' . $suffix);
            if ($value !== false) {
                $db[$key] = $value;
            }
        }
        if ($db['user'] === '') {
            error_log('EDA: configure DB_USER ou o usuário em config/database.local.php no servidor PHP.');
            throw new PDOException('Usuário do banco não configurado.');
        }
        $port = (int) ($db['port'] ?? 3306);
        $dsn = "mysql:host={$db['host']};port={$port};"
            . "dbname={$db['name']};charset={$db['charset']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $ca = getenv('DB_SSL_CA') ?: ($db['ssl_ca'] ?? '');
        if ($ca !== '') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $ca;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }

        self::$pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
        
        return self::$pdo;
    }
}
