<?php
namespace sistema\Servicos\Finance;

interface FinanceInterface
{
    public function sumUserRevenueByPeriod(string $startDate, string $endDate, int $userId): float;
    public function sumUserExpensesByPeriod(string $startDate, string $endDate, int $userId): float;
    public function findCashBalanceByPeriod(string $startDate, string $endDate, int $userId): float;
    public function calculateMargin(float $profit, float $revenue): float;
}
