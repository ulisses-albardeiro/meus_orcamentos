<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;

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
