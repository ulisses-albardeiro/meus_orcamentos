<?php

namespace sistema\Servicos\Finance;

use DateTime;
use sistema\Modelos\CategoryModel;
use sistema\Modelos\ExpenseModel;
use sistema\Modelos\RevenueModel;
use sistema\Nucleo\Helpers;

class DashboardService implements DashboardInterface
{
    public function __construct(
        private RevenueModel $revenueModel,
        private ExpenseModel $expenseModel,
        private CategoryModel $categoryModel
    ) {}

    public function sumRevenueByPeriod(string $startDate, string $endDate, int $userId): float
    {
        $revenues = $this->revenueModel->findRevenuesByRangeDate($startDate, $endDate, $userId);

        if (empty($revenues)) {
            return 0;
        }
        $revenues = Helpers::centsToReais($revenues, 'valor');
        $totalRevenue = array_sum(array_column($revenues, 'valor'));


        return $totalRevenue;
    }

    public function sumExpensesByPeriod(string $startDate, string $endDate, int $userId): float
    {
        $expenses = $this->expenseModel->findExpensesByRangeDate($startDate, $endDate, $userId);

        if (empty($expenses)) {
            return 0;
        }

        $expenses = Helpers::centsToReais($expenses, 'valor');
        $totalExpenses = array_sum(array_column($expenses, 'valor'));
        return $totalExpenses;
    }

    public function findCashBalanceByPeriod(string $startDate, string $endDate, int $userId): float
    {
        $totalExpenses = $this->sumExpensesByPeriod($startDate, $endDate, $userId);
        $totalRevenue = $this->sumRevenueByPeriod($startDate, $endDate, $userId);

        return $totalRevenue - $totalExpenses;
    }

    public function calculateMargin(float $profit, float $revenue): float
    {
        if (empty($revenue)) {
            return 0;
        }

        return round(($profit / $revenue) * 100, 2);
    }


    public function findQuarterlyFiananceData(string $endDate, int $userId): array
    {
        $quarter = new DateTime($endDate);
        $quarter->modify('-2 months');
        $startDate = $quarter->format('Y-m-d');

        $revenues = $this->revenueModel->findRevenuesByRangeDate($startDate, $endDate, $userId) ?? [];
        $revenues = Helpers::centsToReais($revenues, 'valor');

        $expenses = $this->expenseModel->findExpensesByRangeDate($startDate, $endDate, $userId) ?? [];
        $expenses = Helpers::centsToReais($expenses, 'valor');

        $monthsDisplayed = [
            date('Y-m', strtotime('first day of -2 months', strtotime($endDate))),
            date('Y-m', strtotime('first day of -1 month', strtotime($endDate))),
            date('Y-m', strtotime($endDate))
        ];

        $revenuesGroup = [];
        foreach ($revenues as $revenue) {
            $month = date('Y-m', strtotime($revenue->dt_receita));
            $revenuesGroup[$month] = ($revenuesGroup[$month] ?? 0) + ($revenue->valor);
        }

        $expensesGroup = [];
        foreach ($expenses as $expense) {
            $month = date('Y-m', strtotime($expense->dt_despesa));
            $expensesGroup[$month] = ($expensesGroup[$month] ?? 0) + ($expense->valor);
        }

        $translatedMonths = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];

        $labels = [];
        $reavenueValues = [];
        $expensesValues = [];

        foreach ($monthsDisplayed as $month) {
            $numberMonth = explode('-', $month)[1];
            $labels[] = $translatedMonths[$numberMonth];
            $reavenueValues[] = $revenuesGroup[$month] ?? 0;
            $expensesValues[] = $expensesGroup[$month] ?? 0;
        }

        return [
            'labels' => $labels,
            'reavenues' => $reavenueValues,
            'expenses' => $expensesValues
        ];
    }

    public function getExpensesByCategory(string $startDate, string $endDate, int $userId): array
    {
        $expenses = $this->expenseModel->findExpensesByRangeDate($startDate, $endDate, $userId);
        $expenses = Helpers::centsToReais($expenses, 'valor');

        $categories = $this->categoryModel->findCategoryByUserId($userId);

        if (empty($expenses) || empty($categories)) {
            return ['labels' => [], 'values' => []];
        }

        $categoryMap = [];
        foreach ($categories as $category) {
            
            if (strtolower($category->tipo) === 'despesas') {
                $categoryMap[$category->id] = $category->nome;
            }
        }

        $expenseTotals = array_fill_keys(array_keys($categoryMap), 0);

        foreach ($expenses as $expense) {
            $catId = $expense->id_categoria;
            if (isset($expenseTotals[$catId])) {
                $expenseTotals[$catId] += ($expense->valor);
            }
        }

        $labels = [];
        $values = [];
        foreach ($expenseTotals as $catId => $total) {
            $labels[] = $categoryMap[$catId];
            $values[] = $total;
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }
}
