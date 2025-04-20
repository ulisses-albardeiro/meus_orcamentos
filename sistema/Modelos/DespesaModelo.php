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
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->dt_despesa = $dados["data"];
        $this->id_usuario = $id_usuario;
        if ($this->salvar()) {
            return true;
        }
        return false;
    }

    public function getDespesasPorCategoria(string $data, string $data_final, int $id_usuario): array
    {
        $despesas_por_categoria = $this->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$id_usuario}")->resultado(true) ?? [];

        return $despesas_por_categoria;
    }

    public function getDespesasPorData(string $data, string $data_final, int $id_usuario) : array
    {
        $despesas = $this->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$id_usuario}")->resultado(true) ?? [];
        return $despesas;    
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

    public function getDespesaTrimestral(string $data_inicio, string $data_fim, int $id_usuario) : array
    {
        $despesa_trimestral = $this->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$data_inicio}&:fim={$data_fim}&:id={$id_usuario}")->resultado(true)??[];    

        return $despesa_trimestral;
    }
}
