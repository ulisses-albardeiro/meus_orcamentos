<?php

namespace App\Models;

use App\Core\Modelo;

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
