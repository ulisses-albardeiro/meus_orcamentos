<?php

namespace App\Services\Finance;

interface DashboardInterface
{
    public function sumRevenueByPeriod(string $startDate, string $endDate, int $userId): float;
    public function sumExpensesByPeriod(string $startDate, string $endDate, int $userId): float;
    public function findCashBalanceByPeriod(string $startDate, string $endDate, int $userId): float;
    public function findQuarterlyFiananceData(string $endDate, int $userId): ?array;
    public function calculateMargin(float $profit, float $revenue): float;
    public function getExpensesByCategory(string $startDate, string $endDate, int $userId): array;
}
