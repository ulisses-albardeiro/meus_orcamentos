<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class CategoriaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("categorias");
    }

    public function getCategorias(int $id_usuario) : array
    {
        $categorias = $this->busca("id_usuario = {$id_usuario}")->ordem("id DESC")->resultado(true) ?? [];
        return $categorias;
    }

    public function cadastrarCategoria(array $dados, int $id_usuario): bool
    {
        $this->nome = $dados['nome'];
        $this->tipo = $dados['tipo'];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $id_usuario;
        if ($this->salvar()) {
            return true;
        }
        return false;
    }

    public function editarCategoria(array $dados, int $id_categoria): bool
    {
        $this->id = $id_categoria;
        $this->nome = $dados['nome'];
        if ($this->salvar()) {
            return true;
        }
        return false;
    }

    public function categoriaExiste(array $dados, int $id_usuario) : bool
    {
        $resultado = $this->busca("nome = :n AND tipo = :t AND id_usuario = :id", ":n={$dados['nome']}&:t={$dados['tipo']}&:id={$id_usuario}")->resultado();
        if (!empty($resultado)) {
            return true;
        }
        return false;
    }
}
