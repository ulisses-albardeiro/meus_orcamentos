<?php

namespace App\Controllers\Panel\Blog;

use App\Controllers\Panel\PanelController;

class BlogControlador extends PanelController
{
    public function listar() : void
    {
        echo $this->template->rendenizar('admin/categorias.html', []);    
    }
}