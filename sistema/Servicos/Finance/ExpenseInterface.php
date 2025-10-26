<?php

namespace sistema\Servicos\Finance;

interface ExpenseInterface
{
    public function updateExpense(array $data, int $id): bool;
    public function destroyExpense(int $id): bool;
    public function createExpense(array $data, int $userId): bool;
    public function findExpensesByUserId(int $userId): ?array;
}
