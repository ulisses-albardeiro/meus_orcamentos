<?php

namespace App\Models;

use App\Core\Model;

class BlogCategoriaModelo extends Model
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
