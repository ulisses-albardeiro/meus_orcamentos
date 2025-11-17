<?php

namespace App\Controllers;

use App\Models\BlogCategoriaModelo;
use App\Models\BlogPostModelo;
use App\Core\Controller;

class BlogController extends Controller
{
    protected BlogPostModelo $postModelo;
    protected BlogCategoriaModelo $categoriaModelo;

    public function __construct(BlogPostModelo $postModelo, BlogCategoriaModelo $categoriaModelo)
    {
        parent::__construct('templates/views/blog');
        $this->postModelo = $postModelo;
        $this->categoriaModelo = $categoriaModelo;
    }

    public function index(): void
    {
        echo $this->template->render(
            "index.html",
            [
                'title' => 'Blog Meus Orçamentos',
                'categorias' => $this->categoriaModelo->buscaCategorias(),
                'posts' => $this->postModelo->buscaPost(),
            ]
        );
    }
}
