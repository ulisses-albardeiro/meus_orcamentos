<?php

namespace App\Core;

use PDO;
use PDOException;

class Connection
{
    private static $instancia;

    public static function getInstancia(): PDO
    {
        if (empty(self::$instancia)) {
            try {
                self::$instancia = new PDO(
                    'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';charset=utf8mb4',
                    DB_USER,
                    DB_PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_CASE => PDO::CASE_NATURAL,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                    ]
                );
            } catch (PDOException $e) {
                exit("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }

    protected function __construct() {}

    private function __clone(): void {}
}
