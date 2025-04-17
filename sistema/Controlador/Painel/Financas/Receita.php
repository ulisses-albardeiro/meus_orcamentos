<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\ReceitaModelo;
use sistema\Nucleo\Helpers;

class Receita extends PainelControlador
{
    public function listar(): void
    {
        $receitas = (new ReceitaModelo)->busca("id_usuario = {$this->usuario->id}")->resultado(true) ?? [];
        $categorias = (new CategoriaModelo)->busca("id_usuario = {$this->usuario->id}")->resultado(true) ?? [];
        echo $this->template->rendenizar(
            "financas/receitas.html",
            [
                "receitas" => $this->getNomeCategoria($receitas, $categorias),
                "categorias" => $categorias,
                "tipo" => "Receita"   
            ]
        );
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new ReceitaModelo);

        if ($salvar->cadastrarReceita($dados, $this->usuario->id)) {
            $this->mensagem->mensagemSucesso("Receita Cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        } else {
            $this->mensagem->mensagemErro("ERRO: " . $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function excluir(int $id_receita) : void
    {
        $excluir = (new ReceitaModelo);
        if ($excluir->apagar("id = {$id_receita}")) {
            $this->mensagem->mensagemSucesso("Receita excluida com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::voltar();
        }
    }

    public function editar(int $id_receita) : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new ReceitaModelo);
        if ($salvar->editarReceita($dados, $id_receita)) {
            $this->mensagem->mensagemSucesso("Receita editada com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado".$salvar->getErro())->flash();
            Helpers::voltar();
        }
        ;
    }

    private function getNomeCategoria(array $receitas, array $categorias): array
    {
        $receitas = array_map(function ($receita) use ($categorias) {
            foreach ($categorias as $categoria) {
                if ($receita->id_categoria == $categoria->id) {
                    $receita->categoria = $categoria->nome;
                }
            }
            return $receita;
        }, $receitas);
        return $receitas;
    }
}
