<?php

namespace App\Models;

use App\Core\Model;

class BlogPostModelo extends Model
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
