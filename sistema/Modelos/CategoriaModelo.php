<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class CategoriaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("categorias");
    }

    public function cadastrarCategoria(array $dados, int $id_usuario): bool
    {
        $this->nome = $dados['nome'];
        $this->tipo = $dados['tipo'];
        $this->id_usuario = $id_usuario;
        if ($this->salvar()) {
            return true;
        }
        return false;
    }
}
