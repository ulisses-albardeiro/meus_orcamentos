<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;

class Dashboard extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar("dashboard-financeiro.html", []);    
    }
}