<?php

namespace sistema\Controlador\Painel\Admin;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ListaModelo;
use sistema\Modelos\OrcamentoModelo;
use sistema\Modelos\ReciboModelo;
use sistema\Modelos\UsuarioModelo;

class Admin extends PainelControlador
{
    public function usuarios(): void
    {
        echo $this->template->rendenizar(
            "admin/usuarios.html",
            [
                "usuarios" => (new UsuarioModelo)->busca()->resultado(true),
                "orcamentos" => (new OrcamentoModelo)->busca()->resultado(true),
                "listas" => (new ListaModelo)->busca()->resultado(true),
                "recibos" => (new ReciboModelo)->busca()->resultado(true),
                'link_usuarios' => 'active'
            ]
        );
    }

    public function orcamentos(): void
    {
        echo $this->template->rendenizar(
            "admin/orcamentos.html",
            [
                "usuarios" => (new UsuarioModelo)->busca()->resultado(true),
                "orcamentos" => (new OrcamentoModelo)->busca()->resultado(true),
                'link_orcamentos' => 'active'
            ]
        );
    }

    public function listas(): void
    {
        echo $this->template->rendenizar(
            "admin/listas.html",
            [
                "usuarios" => (new UsuarioModelo)->busca()->resultado(true),
                "listas" => (new ListaModelo)->busca()->resultado(true),
                'link_listas' => 'active'
            ]
        );
    }

    public function recibos(): void
    {
        echo $this->template->rendenizar(
            "admin/recibos.html",
            [
                "usuarios" => (new UsuarioModelo)->busca()->resultado(true),
                "recibos" => (new ReciboModelo)->busca()->resultado(true),
                'link_recibos' => 'active'
            ]
        );
    }
}
