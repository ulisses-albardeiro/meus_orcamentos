<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;

class CadastroOrcamento extends PainelControlador
{
    public function cadastrar(string $cliente, string $vl_total, array $dados) : void
    {
        $orcamento = (new OrcamentoModelo);
        $orcamento->cliente = $cliente;
        $orcamento->vl_total = $vl_total;
        $orcamento->orcamento_completo = json_encode($dados);
        $orcamento->salvar();
    }
}
