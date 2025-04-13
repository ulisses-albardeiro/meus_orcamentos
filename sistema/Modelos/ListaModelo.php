<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ListaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("listas");
    } 

    public function cadastrarLista(string $cliente, array $dados, int $id_usuario) : void
    {
        $this->cliente = $cliente;
        $this->lista_completa = json_encode($dados);
        $this->id_usuario = $id_usuario;
        $this->salvar();
    }
}