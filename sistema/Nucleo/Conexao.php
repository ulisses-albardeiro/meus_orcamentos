<?php

namespace sistema\Nucleo;

use PDO;
use PDOException;

class Conexao
{

    private static $instancia;

    public static function getInstancia(): PDO
    {
        if (empty(self::$instancia)) {

            try {
                self::$instancia = new PDO('mysql:dbname=' . DB_NOME . ';host=' . DB_HOST, DB_USUARIO, DB_SENHA, [
                    //Erros de PDO serão todos exeção
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    //Garante que o mesmo nome da coluna do banco seja utilizado
                    PDO::ATTR_CASE => PDO::CASE_NATURAL,
                    //Converte qualquer resultado em um objeto
                    PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_OBJ
                ]);
            } catch (PDOException $e) {
                exit("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }

    protected function __construct() {}

    private function __clone(): void {}
}
