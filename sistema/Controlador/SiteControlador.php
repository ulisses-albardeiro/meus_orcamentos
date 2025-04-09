<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;

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

    public function erro404(): void
    {
        echo $this->template->rendenizar("404.html", []);
    }
}
