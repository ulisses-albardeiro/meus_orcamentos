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
        $this->value = $data["expense"] * 100;
        $this->category_id = $data["category_id"];
        $this->created_at = date('Y-m-d H:i:s');
        $this->expense_date = $data["date"];
        $this->user_id = $userId;

        return $this->salvar();
    }

    public function findExpensesByCategory(string $startDate, string $endDate, int $userId): array
    {
        $expensesByCategory = $this->busca(
            'dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}"
        )->resultado(true) ?? [];

        return $expensesByCategory;
    }

    public function findExpensesByDate(string $startDate, string $endDate, int $userId): array
    {
        $expenses = $this->busca(
            'dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}"
            )->resultado(true) ?? [];

        return $expenses;
    }

    public function updateExpense(array $data, int $id): bool
    {
        $this->id = $id;
        $this->valor = $data["despesa"]*100;
        $this->dt_despesa = $data["data"];
        return $this->salvar();
    }

    public function findQuarterlyExpenses(string $startDate, string $endDate, int $userId): ?array
    {
        $quarterlyExpenses = $this->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$startDate}&:fim={$endDate}&:id={$userId}")->resultado(true);    

        return $quarterlyExpenses;
    }
}
