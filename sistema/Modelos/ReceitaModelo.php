<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ReceitaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("receitas");
    }

    public function cadastrarReceita(array $dados): bool
    {
        $this->valor = $dados["receita"] * 100;
        $this->id_categoria = $dados["id-categoria"];
        if ($this->salvar()) {
            return true;
        }
        return false;
    }
}
