<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class OrcamentoModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("orcamentos");
    }

    public function buscaOrcamentos(int $id_usuario) : array
    {
        $orcamentos = $this->busca("id_usuario = {$id_usuario}")->resultado(true) ?? [];
        return $orcamentos;    
    }

    public function buscaOrcamentosPorId($id_orcamento)
    {
        $orcamentos = $this->busca("id = {$id_orcamento}")->resultado(true) ?? [];
        return $orcamentos;    
    }

    public function cadastrarOrcamento(string $cliente, string $vl_total, array $dados, int $id_usuario, string $modelo) : ?int
    {
        $this->cliente = $cliente;
        $this->vl_total = $vl_total;
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->orcamento_completo = json_encode($dados);
        $this->id_usuario = $id_usuario;
        $this->modelo = $modelo;
        if ($this->salvar()) {
            return $this->getUltimoId();
        }

        return null;      
    }

    public function excluirOrcamento(int $id_orcamento) : bool
    {
        if ($this->apagar("id = '{$id_orcamento}'")) {
            return true;
        }
        return false;         
    }
}
