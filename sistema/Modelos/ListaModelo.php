<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ListaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("listas");
    } 

    public function buscaListas(int $id_usuario) : ?array
    {
        $listas = $this->busca("id_usuario = {$id_usuario}")->resultado(true);   
        return $listas; 
    }

    public function buscaListaPorHash(string $hash): array
    {
        $lista = $this->busca("hash = '{$hash}'", null, 'id_usuario, lista_completa')->resultado(true);
        return $lista;
    }

     public function buscaListaPorId($id_lista): ?array
    {
        $orcamentos = $this->busca("id = {$id_lista}")->resultado(true);
        return $orcamentos;    
    }

    public function cadastrarLista(int $id_cliente, array $dados, int $id_usuario, string $modelo, string $hash) : bool
    {
        $this->id_cliente = $id_cliente;
        $this->hash = $hash;
        $this->modelo = $modelo;
        $this->lista_completa = json_encode($dados);
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $id_usuario;
        return $this->salvar();
    }

    public function excluirLista(string $hash) : bool
    {
        return $this->apagar("hash = '{$hash}'");
    }
}