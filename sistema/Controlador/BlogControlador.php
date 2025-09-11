<?php

namespace sistema\Controlador;

use sistema\Modelos\BlogCategoriaModelo;
use sistema\Modelos\BlogPostModelo;
use sistema\Modelos\CategoriaModelo;
use sistema\Nucleo\Controlador;

class BlogControlador extends Controlador
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
