<?php

namespace App\Controlador;

use App\Nucleo\Controlador;
use App\Nucleo\Helpers;
use App\Nucleo\Sessao;

class SiteController extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/views/site');
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            "index.html",
            [
                'home' => 'Home'
            ]
        );
    }

    public function pravicyPolicy(): void
    {
        echo $this->template->rendenizar(
            "pravicy-policy.html",
            [
                'titulo' => 'Política de Privacidade'
            ]
        );
    }

    public function error404(): void
    {
        echo $this->template->rendenizar("404.html", []);
    }
}
