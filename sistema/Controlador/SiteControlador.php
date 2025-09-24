<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;

class SiteControlador extends Controlador
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

    public function politicaPrivacidade(): void
    {
        echo $this->template->rendenizar(
            "politica-de-privacidade.html",
            [
                'titulo' => 'Política de Privacidade'
            ]
        );
    }

    public function erro404(): void
    {
        echo $this->template->rendenizar("404.html", []);
    }
}
