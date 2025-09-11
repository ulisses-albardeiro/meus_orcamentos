<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class BlogPostModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("blog_post");
    }

    public function buscaPost(): ?array
    {
        return $this->busca()->resultado(true);
    }
}
