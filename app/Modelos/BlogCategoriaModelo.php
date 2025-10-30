<?php

namespace App\Modelos;

use App\Nucleo\Modelo;

class BlogCategoriaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("blog_categorias");
    }

    public function buscaCategorias(): ?array
    {
        return $this->busca()->resultado(true);
    }
}
