<?php

namespace App\Controllers\Painel;

use App\Core\Controlador;
use App\Core\Sessao;

class PainelControlador extends Controlador
{
    protected Sessao $session;

    public function __construct()
    {
        parent::__construct('templates/views/painel');
        $this->session = (new Sessao);
    }   
}
