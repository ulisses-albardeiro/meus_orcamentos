<?php

use Symfony\Component\Dotenv\Dotenv;

date_default_timezone_set('America/Sao_Paulo');

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__.'/../.env');

// Define as constantes
define('DB_NOME', $_ENV['DB_NOME']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USUARIO', $_ENV['DB_USUARIO']);
define('DB_SENHA', $_ENV['DB_SENHA']);

define('SITE_NAME', $_ENV['SITE_NAME']);
define('SITE_DESC', $_ENV['SITE_DESC']);

define('HOST_EMAIL', $_ENV['HOST_EMAIL']);
define('USUARIO_EMAIL', $_ENV['USUARIO_EMAIL']);
define('SENHA_EMAIL', $_ENV['SENHA_EMAIL']);
define('PORTA_EMAIL', $_ENV['PORTA_EMAIL']);

define('PRODUCTION_URL', $_ENV['PRODUCTION_UR']);
define('DEVELOPMENT_URL', $_ENV['DEVELOPMENT_URL']);
define('URL', $_ENV['URL']);
