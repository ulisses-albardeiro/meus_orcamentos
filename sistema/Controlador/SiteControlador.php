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
        $sessao = (new Sessao)->checarSessao("usuarioId");
        if ($sessao) {
            Helpers::redirecionar("home");
        }

        echo $this->template->rendenizar(
            "index.html",
            [
                'home' => 'Home'
            ]
        );
    }

    public function erro404(): void
    {
        echo $this->template->rendenizar("404.html", []);
    }
}
