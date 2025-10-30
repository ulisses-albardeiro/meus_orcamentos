<?php

namespace App\Controllers\Painel\Finance;

use DateTime;
use App\Controllers\Painel\PainelControlador;
use App\Core\Helpers;
use App\Services\Finance\CategoryInterface;
use App\Services\Finance\DashboardInterface;

class DashboardController extends PainelControlador
{
    public function __construct(
        private DashboardInterface $financeService,
        private CategoryInterface $categoryService)
    {
        parent::__construct();
    }

    public function index(?string $date = null): void
    {
        $date = $date ? date('Y-m-01', strtotime($date)) :  date('Y-m-01');

        $endDate = new DateTime($date);
        $endDate->modify('last day of this month');
        $endDate = $endDate->format('Y-m-d');
        
        $totalRevenueCurrentMonth = $this->financeService->sumRevenueByPeriod($date, $endDate, $this->session->userId);
        $totalExpensesCurrentMonth = $this->financeService->sumExpensesByPeriod($date, $endDate, $this->session->userId);
        $categories = $this->categoryService->findCategoryByUserId($this->session->userId);
        $quarterlyData = $this->financeService->findQuarterlyFiananceData($endDate, $this->session->userId);
        $expensesByCategory = $this->financeService->getExpensesByCategory($date, $endDate, $this->session->userId);
        
        echo $this->template->rendenizar("finances/dashboard.html",
            [
                "categorias" => $categories,
                "totalRevenueCurrentMonth" => $totalRevenueCurrentMonth,
                "totalExpensesCurrentMonth" => $totalExpensesCurrentMonth,
                "balance" => $totalRevenueCurrentMonth - $totalExpensesCurrentMonth,
                "expensesByCategory" => $expensesByCategory,
                "quarterlyData" => $quarterlyData,
                "date" => substr($date, 0, 7),
                "dateInPortuguese" => Helpers::monthInPortuguese($date),
                "titulo" => "Visão Geral Finanças",
                "dashboardMenu" => "active",
            ]
        );
    }
}
