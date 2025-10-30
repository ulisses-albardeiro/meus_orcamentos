<?php

namespace App\Models;

use App\Core\Modelo;

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
