<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class DespesaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("despesas");
    }

    public function cadastrarDespesa(array $dados, int $id_usuario): bool
    {
        $this->valor = $dados["despesa"] * 100;
        $this->id_categoria = $dados["id-categoria"];
        $this->dt_despesa = $dados["data"];
        $this->id_usuario = $id_usuario;
        if ($this->salvar()) {
            return true;
        }
        return false;
    }

    public function editarDespesa(array $dados, int $id_despesa) : bool
    {
        $this->id = $id_despesa;
        $this->valor = $dados["despesa"]*100;
        $this->dt_despesa = $dados["data"];
        if ($this->salvar()) {
            return true;
        }

        return false;
    }
}
