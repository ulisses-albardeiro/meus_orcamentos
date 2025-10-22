<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\Finance\Servicos\ServicoReceita;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\ReceitaModelo;
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
        $receitas = (new ReceitaModelo)->busca("id_usuario = {$this->usuario->usuarioId}")->resultado(true) ?? [];
        $categorias = (new CategoriaModelo)->busca("id_usuario = {$this->usuario->usuarioId} AND tipo = 'Receitas'")->resultado(true) ?? [];
        echo $this->template->rendenizar(
            "financas/receitas.html",
            [
                "receitas" => $this->servico->getNomeCategoria($receitas, $categorias),
                "categorias" => $categorias,
                "tipo" => "Receitas",
                'titulo' => 'Receitas'
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new ReceitaModelo);

        if ($salvar->cadastrarReceita($dados, $this->usuario->usuarioId)) {
            $this->mensagem->mensagemSucesso("Receita Cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        } else {
            $this->mensagem->mensagemErro("ERRO: " . $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function destroy(int $id) : void
    {
        $excluir = (new ReceitaModelo);
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
        $salvar = (new ReceitaModelo);
        if ($salvar->editarReceita($dados, $id)) {
            $this->mensagem->mensagemSucesso("Receita editada com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado".$salvar->getErro())->flash();
            Helpers::voltar();
        }
        ;
    }
}
