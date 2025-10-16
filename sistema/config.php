<?php

use Symfony\Component\Dotenv\Dotenv;

date_default_timezone_set('America/Sao_Paulo');

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__.'/../.env');

// Define as constantes
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

define('SITE_NAME', $_ENV['SITE_NAME']);
define('SITE_DESC', $_ENV['SITE_DESC']);

define('HOST_EMAIL', $_ENV['HOST_EMAIL']);
define('EMAIL_USER', $_ENV['EMAIL_USER']);
define('EMAIL_PASSWORD', $_ENV['EMAIL_PASSWORD']);
define('EMAIL_PORT', $_ENV['EMAIL_PORT']);

define('PRODUCTION_URL', $_ENV['PRODUCTION_URL']);
define('DEVELOPMENT_URL', $_ENV['DEVELOPMENT_URL']);
define('URL', $_ENV['URL']);
