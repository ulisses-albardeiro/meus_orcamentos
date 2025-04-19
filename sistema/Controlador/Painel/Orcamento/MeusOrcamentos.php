<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;

class MeusOrcamentos extends PainelControlador
{
    public function listar(): void
    {
        $orcamentos = (new OrcamentoModelo)->busca("id_usuario = {$this->usuario->id}")->resultado(true);

        echo $this->template->rendenizar("meus-orcamentos.html", [
            'orcamentos' => $orcamentos
        ]);
    }
}