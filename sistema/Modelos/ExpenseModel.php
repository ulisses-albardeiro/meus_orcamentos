<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class ExpenseModel extends Modelo
{
    public function __construct()
    {
        parent::__construct("despesas");
    }

    public function createExpense(array $data, int $userId): bool
    {
        $this->valor = $data["value"] * 100;
        $this->id_categoria = $data["category_id"];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->dt_despesa = $data["date"];
        $this->id_usuario = $userId;

        return $this->salvar();
    }

    public function findExpensesByCategory(string $startDate, string $endDate, int $userId): array
    {
        $expensesByCategory = $this->busca(
            'dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}"
        )->resultado(true) ?? [];

        return $expensesByCategory;
    }

    public function findExpensesByRangeDate(string $startDate, string $endDate, int $userId): ?array
    {
        $expenses = $this->busca(
            'dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}"
            )->resultado(true);

        return $expenses;
    }

    public function updateExpense(array $data, int $id): bool
    {
        $this->id = $id;
        $this->valor = $data["value"]*100;
        $this->dt_despesa = $data["date"];
        return $this->salvar();
    }
}
