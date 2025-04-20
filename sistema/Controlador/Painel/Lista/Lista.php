<?php

namespace sistema\Controlador\Painel\Lista;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ListaModelo;
use sistema\Nucleo\Helpers;

class Lista extends PainelControlador
{
    public function listar(): void
    {
        $listas = (new ListaModelo)->getListas($this->usuario->id);

        echo $this->template->rendenizar("listas/minhas-listas.html", 
        [
            'listas' => $listas
        ]);
    }

    public function cadastrar(): void
    {
        echo $this->template->rendenizar("listas/criar-lista.html", []);
    }

    public function excluir(int $id_lista): void
    {
        $excluir = (new ListaModelo);
        if ($excluir->excluirLista($id_lista)) {
            $this->mensagem->mensagemSucesso("Lista excluida com sucesso")->flash();
            Helpers::redirecionar("minhas-listas");
        }else {
            $this->mensagem->mensagemErro("ERRO: ".$excluir->getErro())->flash();
            Helpers::redirecionar("minhas-listas");
        }
    }
}
