<?php

namespace app\Controlador;

use app\Modelos\BlogCategoriaModelo;
use app\Modelos\BlogPostModelo;
use app\Nucleo\Controlador;

class BlogController extends Controlador
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
        echo $this->template->rendenizar(
            "index.html",
            [
                'titulo' => 'Blog Meus Orçamentos',
                'categorias' => $this->categoriaModelo->buscaCategorias(),
                'posts' => $this->postModelo->buscaPost(),
            ]
        );
    }
}
