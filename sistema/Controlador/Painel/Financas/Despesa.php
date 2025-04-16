<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\DespesaModelo;
use sistema\Nucleo\Helpers;

class Despesa extends PainelControlador
{
    public function listar(): void
    {
        $despesas = (new DespesaModelo)->busca("id_usuario = {$this->usuario->id}")->resultado(true) ?? [];
        $categorias = (new CategoriaModelo)->busca("id_usuario = {$this->usuario->id}")->resultado(true) ?? [];
        echo $this->template->rendenizar(
            "financas/despesas.html",
            [
                "despesas" => $this->getNomeCategoria($despesas, $categorias),
                "categorias" => $categorias,
                "tipo" => "despesa"      
            ]
        );
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new DespesaModelo);

        if ($salvar->cadastrarDespesa($dados, $this->usuario->id)) {
            $this->mensagem->mensagemSucesso("Despesa Cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function excluir(int $id_despesa) : void
    {
        $excluir = (new DespesaModelo);
        if ($excluir->apagar("id = {$id_despesa}")) {
            $this->mensagem->mensagemSucesso("Despesa excluida com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::voltar();
        }
    }

    public function editar(int $id_receita) : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new DespesaModelo);
        if ($salvar->editarDespesa($dados, $id_receita)) {
            $this->mensagem->mensagemSucesso("Despesa editada com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado".$salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    private function getNomeCategoria(array $despesas, array $categorias): array
    {
        $despesas = array_map(function ($despesa) use ($categorias) {
            foreach ($categorias as $categoria) {
                if ($despesa->id_categoria == $categoria->id) {
                    $despesa->categoria = $categoria->nome;
                }
            }
            return $despesa;
        }, $despesas);
        return $despesas;
    }
}
