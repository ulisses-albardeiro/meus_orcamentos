<?php

namespace sistema\Controlador\Painel\Admin;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ListaModelo;
use sistema\Modelos\OrcamentoModelo;
use sistema\Modelos\ReciboModelo;
use sistema\Modelos\UsuarioModelo;

class Admin extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar("admin/listar.html", 
        [
            "usuarios" => (new UsuarioModelo)->busca()->resultado(true),
            "orcamentos" => (new OrcamentoModelo)->busca()->resultado(true),
            "listas" => (new ListaModelo)->busca()->resultado(true),
            "recibos" => (new ReciboModelo)->busca()->resultado(true)
        ]);    
    }
}