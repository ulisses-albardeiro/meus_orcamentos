<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Nucleo\Helpers;

class CategoryController extends PainelControlador
{
    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new CategoriaModelo);

        if ($salvar->categoriaExiste($dados, $this->usuario->userId)) {
            $this->mensagem->mensagemAtencao("A categoria {$dados['nome']} do tipo {$dados['tipo']} já está cadastrada!")->flash();
            Helpers::voltar();
        }

        if ($salvar->cadastrarCategoria($dados, $this->usuario->userId)) {
            $this->mensagem->mensagemSucesso("Categoria cadastrada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function index() : void
    {
        echo $this->template->rendenizar("financas/categorias.html", 
        [
            "categorias" => (new CategoriaModelo)->getCategorias($this->usuario->userId),
            "tipos" => ["Despesas", "Receitas"],
            'titulo' => 'Categoria'
        ]);    
    }

    public function update(int $id) : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $salvar = (new CategoriaModelo);

        if ($salvar->editarCategoria($dados, $id)) {
            $this->mensagem->mensagemSucesso("Categoria editada com Sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("ERRO: ". $salvar->getErro())->flash();
            Helpers::voltar();
        }
    }

    public function destroy(int $id) : void
    {
        $excluir = (new CategoriaModelo);
        if ($excluir->apagar("id = {$id}")) {
            $this->mensagem->mensagemSucesso("Categoria excluida com sucesso!")->flash();
            Helpers::voltar();
        }else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::voltar();
        }
    }
}
