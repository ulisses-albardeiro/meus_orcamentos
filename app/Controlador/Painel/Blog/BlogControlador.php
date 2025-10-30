<?php

namespace app\Controlador\Painel\Blog;

use app\Controlador\Painel\PainelControlador;

class BlogControlador extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar('admin/categorias.html', []);    
    }
}