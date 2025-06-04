<?php

use sistema\Nucleo\Helpers;

date_default_timezone_set('America/Sao_Paulo');

if (Helpers::localhost()) {
    define('DB_NOME', 'meus_orcamentos');
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', 'root');
    define('DB_SENHA', '123');

    define('SITE_NAME', 'Meus Orçamentos');
    define('SITE_DESC', '');

    define('HOST_EMAIL', 'smtp.hostinger.com');
    define('USUARIO_EMAIL', 'contato@meusorcamentos.com.br');
    define('SENHA_EMAIL', 'y2Z#B&b2');
    define('PORTA_EMAIL', 465);

    define('DEVELOPMENT_URL', 'http://localhost/meus_orcamentos');
    define('URL', '/meus_orcamentos/');
} else {
    define('DB_NOME', 'u997346094_blog');
    define('DB_HOST', 'localhost');
    define('DB_USUARIO', 'u997346094_blog');
    define('DB_SENHA', 'hAtmMv+8');

    define('SITE_NAME', 'Meus Orçamentos');
    define('SITE_DESC', '');

    define('HOST_EMAIL', 'smtp.hostinger.com');
    define('USUARIO_EMAIL', 'contato@meusorcamentos.com.br');
    define('SENHA_EMAIL', 'y2Z#B&b2');
    define('PORTA_EMAIL', 465);

    define('PRODUCTION_URL', 'https://meusorcamentos.com.br');
    define('URL', '/');
}