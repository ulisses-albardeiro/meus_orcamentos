<?php

namespace sistema\Servicos\Finance;

use sistema\Modelos\ExpenseModel;
use sistema\Modelos\RevenueModel;

class FinanceService implements FinanceInterface
{
    public function __construct(private RevenueModel $revenueModel, private ExpenseModel $expenseModel){}

    public function sumUserRevenueByPeriod(string $startDate, string $endDate, int $userId): float
    {
        $revenues = $this->revenueModel->findRevenuesByDate($startDate, $endDate, $userId);

        if (empty($revenues)) {
            return 0;
        }
        $totalRevenue = array_sum(array_column($revenues, 'valor'));

        
        return $totalRevenue / 100;
    }

    public function sumUserExpensesByPeriod(string $startDate, string $endDate, int $userId): float
    {
        $expenses = $this->expenseModel->findExpensesByDate($startDate, $endDate, $userId);

        if (empty($expenses)) {
            return 0;
        }

        $totalExpenses = array_sum(array_column($expenses, 'valor'));
        return $totalExpenses / 100;
    }

    public function findCashBalanceByPeriod(string $startDate, string $endDate, int $userId): float
    {
        $totalExpenses = $this->sumUserExpensesByPeriod($startDate, $endDate, $userId);
        $totalRevenue = $this->sumUserRevenueByPeriod($startDate, $endDate, $userId);

        return $totalRevenue - $totalExpenses;
    }

    public function calculateMargin(float $profit, float $revenue): float
    {
        if (empty($revenue)) {
            return 0;
        }

        return round(($profit / $revenue) * 100, 2);
    }
}
