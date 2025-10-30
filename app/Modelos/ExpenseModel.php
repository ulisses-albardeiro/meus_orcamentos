<?php

namespace app\Modelos;

use app\Nucleo\Modelo;

class ExpenseModel extends Modelo
{
    public function __construct()
    {
        parent::__construct("despesas");
    }
    public function findExpensesByUserId(int $userId): ?array
    {
        return $this->busca("id_usuario = {$userId}")->resultado(true);
    }

    public function createExpense(array $data, int $userId): bool
    {
        $this->valor = $data["expense"];
        $this->id_categoria = $data["category_id"];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->dt_despesa = $data["date"];
        $this->id_usuario = $userId;

        return $this->salvar();
    }

     public function updateExpense(array $data, int $id): bool
    {
        $this->id = $id;
        $this->valor = $data["expense"];
        $this->dt_despesa = $data["date"];
        return $this->salvar();
    }

    public function destroyExpense(int $id): bool
    {
        return $this->apagar("id = {$id}");
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
}
