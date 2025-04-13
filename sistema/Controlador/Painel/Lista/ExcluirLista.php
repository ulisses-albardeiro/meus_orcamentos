<?php
namespace sistema\Controlador\Painel\Lista;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ListaModelo;
use sistema\Nucleo\Helpers;

class ExcluirLista extends PainelControlador
{
    public function excluir(int $id_lista) : void
    {
        (new ListaModelo)->apagar("id = '{$id_lista}'");
        $this->mensagem->mensagemSucesso("Lista excluida com sucesso")->flash();
        Helpers::redirecionar("minhas-listas");  
    }
}