<?php

namespace app\Controlador\Painel;

use app\Nucleo\Controlador;
use app\Nucleo\Sessao;

class PainelControlador extends Controlador
{
    protected Sessao $session;

    public function __construct()
    {
        parent::__construct('templates/views/painel');
        $this->session = (new Sessao);
    }   
}
