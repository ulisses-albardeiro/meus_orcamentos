<?php

namespace App\Servicos\Finance;

use App\Modelos\ExpenseModel;
use App\Nucleo\Helpers;

class ExpenseService implements ExpenseInterface
{
    public function __construct(private ExpenseModel $expenseModel){}

    public function updateExpense(array $data, int $id): bool
    {
        $data['expense'] = Helpers::ReaisToCentsSingle($data['expense']);
        return $this->expenseModel->updateExpense($data, $id);
    }

    public function destroyExpense(int $id): bool
    {
        return $this->expenseModel->destroyExpense($id);
    }

    public function createExpense(array $data, int $userId): bool
    {
        $data['expense'] = Helpers::ReaisToCentsSingle($data['expense']);
        return $this->expenseModel->createExpense($data, $userId);
    }

    public function findExpensesByUserId(int $userId): array
    {
        $expenses = $this->expenseModel->findExpensesByUserId($userId) ?? [];
        return Helpers::centsToReais($expenses, 'valor');
    }
}