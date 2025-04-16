<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ReceitaModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("receitas");
    }

    public function cadastrarReceita(array $dados, $usuario): bool
    {
        $this->valor = $dados["receita"] * 100;
        $this->id_categoria = $dados["id-categoria"];
        $this->dt_receita = $dados["data"];
        $this->id_usuario = $usuario;
        if ($this->salvar()) {
            return true;
        }
        return false;
    }

    public function editarReceita(array $dados, int $id_receita) : bool
    {
        $this->id = $id_receita;
        $this->valor = $dados["receita"]*100;
        $this->dt_receita = $dados["data"];
        if ($this->salvar()) {
            return true;
        }

        return false;
    }
}
