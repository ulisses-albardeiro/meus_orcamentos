<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\Finance\Servicos\ServicoDespesa;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoryModel;
use sistema\Modelos\ExpenseModel;
use sistema\Nucleo\Helpers;

class ExpenseController extends PainelControlador
{
    private object $servico;

    public function __construct()
    {
        parent::__construct();
        $this->servico = new ServicoDespesa;
    }

    public function index(): void
    {
        $despesas = (new ExpenseModel)->busca("id_usuario = {$this->usuario->userId}")->resultado(true) ?? [];
        $categorias = (new CategoryModel)->busca("id_usuario = {$this->usuario->userId} AND tipo = 'Despesas'")->resultado(true) ?? [];
        echo $this->template->rendenizar(
            "financas/despesas.html",
            [
                "despesas" => $this->servico->getNomeCategoria($despesas, $categorias),
                "categorias" => $categorias,
                "tipo" => "Despesas",
                'titulo' => 'Despesas',      
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new ExpenseModel);

        if ($salvar->createExpense($dados, $this->usuario->userId)) {
            $this->mensagem->mensagemSucesso("Despesa Cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function destroy(int $id) : void
    {
        $excluir = (new ExpenseModel);
        if ($excluir->apagar("id = {$id}")) {
            $this->mensagem->mensagemSucesso("Despesa excluida com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::voltar();
        }
    }

    public function update(int $id) : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new ExpenseModel);
        if ($salvar->updateExpense($dados, $id)) {
            $this->mensagem->mensagemSucesso("Despesa editada com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado".$salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

}
