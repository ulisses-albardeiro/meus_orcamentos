<?php

namespace App\Controlador\Painel;

use App\Nucleo\Controlador;
use App\Nucleo\Sessao;

class PainelControlador extends Controlador
{
    protected Sessao $session;

    public function __construct()
    {
        parent::__construct('templates/views/painel');
        $this->session = (new Sessao);
    }   
}
