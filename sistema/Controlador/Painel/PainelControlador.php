<?php

namespace sistema\Controlador\Painel;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Sessao;

class PainelControlador extends Controlador
{
    protected Sessao $usuario;

    public function __construct()
    {
        parent::__construct('templates/views/painel');
        $this->usuario = (new Sessao);
    }   
}
