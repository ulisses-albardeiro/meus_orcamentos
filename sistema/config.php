<?php

use sistema\Nucleo\Helpers;

date_default_timezone_set('America/Sao_Paulo');

if (Helpers::localhost()) {
    define('DB_NOME', 'meus_orcamentos');
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', 'root');
    define('DB_SENHA', '');

    define('SITE_NAME', 'Meus Orçamentos');
    define('SITE_DESC', '');

    define('HOST_EMAIL', 'smtp.hostinger.com');
    define('USUARIO_EMAIL', '');
    define('SENHA_EMAIL', '@');
    define('PORTA_EMAIL', 465);

    define('DEVELOPMENT_URL', 'http://localhost/pessoal/meus_orcamentos');
    define('URL', '/pessoal/meus_orcamentos/');
} else {
    define('DB_NOME', '');
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', '');
    define('DB_SENHA', '');

    define('SITE_NAME', 'Meus Orçamentos');
    define('SITE_DESC', '');

    define('HOST_EMAIL', 'smtp.hostinger.com');
    define('USUARIO_EMAIL', '');
    define('SENHA_EMAIL', '@');
    define('PORTA_EMAIL', 465);

    define('PRODUCTION_URL', 'https://meusorcamentos.online');
    define('URL', '/');
}
