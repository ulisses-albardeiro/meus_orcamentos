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

        if ($salvar->cadastrarCategoria($dados, $this->usuario->id)) {
            $this->mensagem->mensagemSucesso("Categoria Cadastrada com Sucesso!")->flash();
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
            "categorias" => (new CategoriaModelo)->busca("id_usuario = {$this->usuario->id}")->resultado(true)
        ]);    
    }
}
