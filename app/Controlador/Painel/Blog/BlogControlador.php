<?php

namespace App\Controlador\Painel\Blog;

use App\Controlador\Painel\PainelControlador;

class BlogControlador extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar('admin/categorias.html', []);    
    }
}