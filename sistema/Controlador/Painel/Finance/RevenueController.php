<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\Finance\Servicos\ServicoReceita;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoryModel;
use sistema\Modelos\RevenueModel;
use sistema\Nucleo\Helpers;

class RevenueController extends PainelControlador
{
    private object $servico;

    public function __construct()
    {
        parent::__construct();
        $this->servico = new ServicoReceita;
    }
    
    public function index(): void
    {
        $receitas = (new RevenueModel)->busca("id_usuario = {$this->usuario->userId}")->resultado(true) ?? [];
        $categorias = (new CategoryModel)->busca("id_usuario = {$this->usuario->userId} AND tipo = 'Receitas'")->resultado(true) ?? [];
        echo $this->template->rendenizar(
            "finances/revenues.html",
            [
                "receitas" => $this->servico->getNomeCategoria($receitas, $categorias),
                "categorias" => $categorias,
                "tipo" => "Receitas",
                'titulo' => 'Receitas',
                "revenueMenu" => "active",
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new RevenueModel);

        if ($salvar->createRevenue($dados, $this->usuario->userId)) {
            $this->mensagem->mensagemSucesso("Receita Cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        } else {
            $this->mensagem->mensagemErro("ERRO: " . $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function destroy(int $id) : void
    {
        $excluir = (new RevenueModel);
        if ($excluir->apagar("id = {$id}")) {
            $this->mensagem->mensagemSucesso("Receita excluida com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::voltar();
        }
    }

    public function update(int $id) : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new RevenueModel);
        if ($salvar->updateRevenue($dados, $id)) {
            $this->mensagem->mensagemSucesso("Receita editada com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado".$salvar->getErro())->flash();
            Helpers::voltar();
        }
        ;
    }
}
