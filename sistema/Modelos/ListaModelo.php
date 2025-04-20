<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ListaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("listas");
    } 

    public function getListas(int $id_usuario) : array
    {
        $listas = $this->busca("id_usuario = {$id_usuario}")->resultado(true) ?? [];   
        return $listas; 
    }

    public function cadastrarLista(string $cliente, array $dados, int $id_usuario) : void
    {
        $this->cliente = $cliente;
        $this->lista_completa = json_encode($dados);
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $id_usuario;
        $this->salvar();
    }

    public function excluirLista(int $id_lista) : bool
    {
        if ($this->apagar("id = '{$id_lista}'")) {
            return true;
        }
        return false;
    }
}