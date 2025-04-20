<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class OrcamentoModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("orcamentos");
    }

    public function getOrcamentos(int $id_usuario) : array
    {
        $orcamentos = $this->busca("id_usuario = {$id_usuario}")->resultado(true) ?? [];
        return $orcamentos;    
    }

    public function cadastrarOrcamento(string $cliente, string $vl_total, array $dados, int $id_usuario) : bool
    {
        $this->cliente = $cliente;
        $this->vl_total = $vl_total;
        $this->orcamento_completo = json_encode($dados);
        $this->id_usuario = $id_usuario;
        if ($this->salvar()) {
            return true;
        }

        return false;
        
    }

    public function excluirOrcamento(int $id_orcamento) : bool
    {
        if ($this->apagar("id = '{$id_orcamento}'")) {
            return true;
        }
        return false;
            
    }
}
