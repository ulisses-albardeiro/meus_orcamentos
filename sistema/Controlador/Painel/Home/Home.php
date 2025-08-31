<?php
namespace sistema\Controlador\Painel\Home;

use sistema\Controlador\Painel\PainelControlador;

class Home extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar("home.html", 
        [
            'titulo' => 'Home',
        ]);    
    }
}