<?php

namespace App\Controllers\Panel\Admin;

use App\Controllers\Panel\PanelController;
use App\Models\ListModel;
use App\Models\QuoteModel;
use App\Models\ReciboModelo;
use App\Models\UserModel;

class Admin extends PanelController
{
    public function usuarios(): void
    {
        echo $this->template->render(
            "admin/usuarios.html",
            [
                "usuarios" => (new UserModel)->busca()->resultado(true),
                "orcamentos" => (new QuoteModel)->busca()->resultado(true),
                "listas" => (new ListModel)->busca()->resultado(true),
                "recibos" => (new ReciboModelo)->busca()->resultado(true),
                'title' => 'Admin Usuários'
            ]
        );
    }

    public function orcamentos(): void
    {
        echo $this->template->render(
            "admin/orcamentos.html",
            [
                "usuarios" => (new UserModel)->busca()->resultado(true),
                "orcamentos" => (new QuoteModel)->busca()->resultado(true),
                'link_orcamentos' => 'active'
            ]
        );
    }

    public function listas(): void
    {
        echo $this->template->render(
            "admin/listas.html",
            [
                "usuarios" => (new UserModel)->busca()->resultado(true),
                "listas" => (new ListModel)->busca()->resultado(true),
                'link_listas' => 'active'
            ]
        );
    }

    public function recibos(): void
    {
        echo $this->template->render(
            "admin/recibos.html",
            [
                "usuarios" => (new UserModel)->busca()->resultado(true),
                "recibos" => (new ReciboModelo)->busca()->resultado(true),
                'link_recibos' => 'active'
            ]
        );
    }
}
