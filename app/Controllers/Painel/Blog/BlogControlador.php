<?php

namespace App\Controllers\Painel\Blog;

use App\Controllers\Painel\PainelControlador;

class BlogControlador extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar('admin/categorias.html', []);    
    }
}