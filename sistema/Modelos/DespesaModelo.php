<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class DespesaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("despesas");
    }

    public function cadastrarDespesa(array $dados): bool
    {
        $this->valor = $dados["despesa"] * 100;
        $this->id_categoria = $dados["id-categoria"];
        if ($this->salvar()) {
            return true;
        }
        return false;
    }
}
