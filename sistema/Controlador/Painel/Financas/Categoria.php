<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Nucleo\Helpers;

class Categoria extends PainelControlador
{
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new CategoriaModelo);

        if ($salvar->categoriaExiste($dados, $this->usuario->id)) {
            $this->mensagem->mensagemAtencao("A categoria {$dados['nome']} do tipo {$dados['tipo']} já está cadastrada!")->flash();
            Helpers::voltar();
        }

        if ($salvar->cadastrarCategoria($dados, $this->usuario->id)) {
            $this->mensagem->mensagemSucesso("Categoria cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function listar() : void
    {
        echo $this->template->rendenizar("financas/categorias.html", 
        [
            "categorias" => (new CategoriaModelo)->getCategorias($this->usuario->id),
            "tipos" => ["Despesas", "Receitas"]
        ]);    
    }

    public function editar(int $id_categoria) : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new CategoriaModelo);

        if ($salvar->editarCategoria($dados, $id_categoria)) {
            $this->mensagem->mensagemSucesso("Categoria editada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function excluir(int $id_categoria) : void
    {
        $excluir = (new CategoriaModelo);
        if ($excluir->apagar("id = {$id_categoria}")) {
            $this->mensagem->mensagemSucesso("Categoria excluida com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::voltar();
        }
    }
}
