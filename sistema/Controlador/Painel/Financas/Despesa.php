<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\DespesaModelo;
use sistema\Nucleo\Helpers;

class Despesa extends PainelControlador
{
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new DespesaModelo);

        if ($salvar->cadastrarDespesa($dados)) {
            $this->mensagem->mensagemSucesso("Despesa Cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }
}
