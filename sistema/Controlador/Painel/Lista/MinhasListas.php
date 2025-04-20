<?php

namespace sistema\Controlador\Painel\Lista;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ListaModelo;

class MinhasListas extends PainelControlador
{
    public function listar(): void
    {
        $listas = (new ListaModelo)->getListas();

        echo $this->template->rendenizar("listas/minhas-listas.html", 
        [
            'listas' => $listas
        ]);
    }
}