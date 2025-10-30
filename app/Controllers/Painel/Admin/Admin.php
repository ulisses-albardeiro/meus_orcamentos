<?php

namespace App\Controllers\Painel\Admin;

use App\Controllers\Painel\PainelControlador;
use App\Models\ListaModelo;
use App\Models\OrcamentoModelo;
use App\Models\ReciboModelo;
use App\Models\UserModel;

class Admin extends PainelControlador
{
    public function usuarios(): void
    {
        echo $this->template->rendenizar(
            "admin/usuarios.html",
            [
                "usuarios" => (new UserModel)->busca()->resultado(true),
                "orcamentos" => (new OrcamentoModelo)->busca()->resultado(true),
                "listas" => (new ListaModelo)->busca()->resultado(true),
                "recibos" => (new ReciboModelo)->busca()->resultado(true),
                'titulo' => 'Admin Usuários'
            ]
        );
    }

    public function orcamentos(): void
    {
        echo $this->template->rendenizar(
            "admin/orcamentos.html",
            [
                "usuarios" => (new UserModel)->busca()->resultado(true),
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
                "usuarios" => (new UserModel)->busca()->resultado(true),
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
                "usuarios" => (new UserModel)->busca()->resultado(true),
                "recibos" => (new ReciboModelo)->busca()->resultado(true),
                'link_recibos' => 'active'
            ]
        );
    }
}
