<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class RevenueModel extends Modelo
{
    public function __construct()
    {
        parent::__construct("receitas");
    }

    public function createRevenue(array $data, int $userId): bool
    {
        $this->valor = $data["receita"] * 100;
        $this->id_categoria = $data["id-categoria"];
        $this->dt_receita = $data["data"];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $userId;
        return $this->salvar();
    }

    public function updateRevenue(array $data, int $id): bool
    {
        $this->id = $id;
        $this->valor = $data["receita"]*100;
        $this->dt_receita = $data["data"];
        
        return $this->salvar();
    }

    public function findRevenuesByDate(string $startDate, string $endDate, int $userId): array
    {
        $receitas = $this->busca('dt_receita >= :inicio AND dt_receita <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}")->resultado(true) ?? [];
        return $receitas;    
    }

    public function findQuarterlyRevenues(string $startDate, string $endDate, int $userId): array
    {
        $receita_trimestral = $this->busca('dt_receita >= :inicio AND dt_receita <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}")->resultado(true)??[];    

        return $receita_trimestral;
    }
}
