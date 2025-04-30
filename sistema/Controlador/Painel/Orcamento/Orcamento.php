<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;

class Orcamento extends PainelControlador
{
    public function listar(): void
    {
        $orcamentos = (new OrcamentoModelo)->getOrcamentos($this->usuario->id);

        echo $this->template->rendenizar(
            "orcamentos/meus-orcamentos.html",
            [
                'orcamentos' => $orcamentos
            ]
        );
    }

    public function formDetalhado(): void
    {
        echo $this->template->rendenizar("orcamentos/form-detalhado.html", []);
    }

    public function formSimples(): void
    {
        echo $this->template->rendenizar("orcamentos/form-simples.html", []);
    }

    public function modelos(): void
    {
        echo $this->template->rendenizar("orcamentos/modelos.html", []);
    }

    public function excluir(int $id_orcamento): void
    {
        $orcamento = (new OrcamentoModelo);
        if ($orcamento->excluirOrcamento($id_orcamento)) {
            $this->mensagem->mensagemSucesso("Orçamento excluido com sucesso")->flash();
            Helpers::redirecionar("meus-orcamentos");
        } else {
            $this->mensagem->mensagemErro("Erro: " . $orcamento->getErro())->flash();
            Helpers::redirecionar("meus-orcamentos");
        }
    }
}
