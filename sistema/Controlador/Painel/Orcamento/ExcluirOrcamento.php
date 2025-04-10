<?php
namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;

class ExcluirOrcamento extends PainelControlador
{
    public function excluir(int $id_orcamento) : void
    {
        (new OrcamentoModelo)->apagar("id = '{$id_orcamento}'");
        $this->mensagem->mensagemSucesso("Orçamento excluido com sucesso")->flash();
        Helpers::redirecionar("meus-orcamentos");  
    }
}