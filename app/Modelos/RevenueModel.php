<?php

namespace app\Modelos;

use app\Nucleo\Modelo;

class RevenueModel extends Modelo
{
    public function __construct()
    {
        parent::__construct("receitas");
    }

    public function findRevenueByUserId(int $userId): ?array
    {
        return $this->busca("id_usuario = {$userId}")->resultado(true);
    }

    public function destroyRevenue(int $id): bool
    {
        return $this->apagar("id = {$id}");
    }

    public function createRevenue(array $data, int $userId): bool
    {
        $this->valor = $data["revenue"];
        $this->id_categoria = $data["category_id"];
        $this->dt_receita = $data["date"];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $userId;
        return $this->salvar();
    }

    public function updateRevenue(array $data, int $id): bool
    {
        $this->id = $id;
        $this->valor = $data["revenue"];
        $this->dt_receita = $data["date"];

        return $this->salvar();
    }

    public function findRevenuesByRangeDate(string $startDate, string $endDate, int $userId): array
    {
        $receitas = $this->busca(
            'dt_receita >= :inicio AND dt_receita <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}"
            )->resultado(true) ?? [];
        return $receitas;
    }
}
