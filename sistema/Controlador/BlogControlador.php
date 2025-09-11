<?php

namespace sistema\Controlador;

use sistema\Nucleo\Controlador;

class BlogControlador extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/views/blog');
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            "index.html",
            [
                'titulo' => 'Blog'
            ]
        );
    }
}
