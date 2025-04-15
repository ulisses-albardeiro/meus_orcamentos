<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class CategoriaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("categorias");
    }

    public function cadastrarCategoria(string $categoria): bool
    {
        $this->nome = $categoria;
        if ($this->salvar()) {
            return true;
        }
        return false;
    }
}
