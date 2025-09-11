<?php

namespace sistema\Controlador\Painel\Blog;

use sistema\Controlador\Painel\PainelControlador;

class BlogControlador extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar('admin/categorias.html', []);    
    }
}