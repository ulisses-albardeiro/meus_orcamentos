<?php

namespace app\Modelos;

use app\Nucleo\Modelo;

class OrcamentoModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("orcamentos");
    }

    public function buscaOrcamentos(int $id_usuario) : ?array
    {
        $orcamentos = $this->busca("id_usuario = {$id_usuario}")->resultado(true);
        return $orcamentos;    
    }

    public function buscaOrcamentosPorId($id_orcamento): ?array
    {
        $orcamentos = $this->busca("id = {$id_orcamento}")->resultado(true);
        return $orcamentos;    
    }

    public function buscaOrcamentosPorHash(string $hash): array
    {
        $orcamentos = $this->busca("hash = '{$hash}'")->resultado(true);
        return $orcamentos;    
    }

    public function cadastrarOrcamento(int $id_cliente, string $vl_total, array $dados, int $id_usuario, string $modelo, string $hash) : ?int
    {
        $this->id_cliente = $id_cliente;
        $this->hash = $hash;
        $this->vl_total = $vl_total/100;
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->orcamento_completo = json_encode($dados);
        $this->id_usuario = $id_usuario;
        $this->modelo = $modelo;
        if ($this->salvar()) {
            return $this->getUltimoId();
        }

        return null;      
    }

    public function excluirOrcamento(string $hash) : bool
    {
        if ($this->apagar("hash = '{$hash}'")) {
            return true;
        }
        return false;         
    }
}
