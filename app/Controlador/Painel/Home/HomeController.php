<?php
namespace App\Controlador\Painel\Home;

use App\Controlador\Painel\PainelControlador;
use App\Nucleo\Helpers;
use App\Servicos\Clients\ClientsInterface;
use App\Servicos\Finance\CategoryInterface;
use App\Servicos\Finance\DashboardInterface;
use App\Servicos\Orcamentos\OrcamentosInterface;

class HomeController extends PainelControlador
{
    public function __construct(
        private DashboardInterface $dashboardService, 
        private OrcamentosInterface $quoteService, 
        private ClientsInterface $clientService,
        private CategoryInterface $categoryService)
    {
        parent::__construct();
    }

    public function index() : void
    {
        $cashBalanceMonth = $this->dashboardService->findCashBalanceByPeriod(date('Y-m-01'), date('Y-m-d'), $this->session->userId);
        $totalRevenueMonth = $this->dashboardService->sumRevenueByPeriod(date('Y-m-01'), date('Y-m-d'), $this->session->userId);
        $totalExpenseMonth = $this->dashboardService->sumExpensesByPeriod(date('Y-m-01'), date('Y-m-d'), $this->session->userId);
        $marginMonth = $this->dashboardService->calculateMargin($cashBalanceMonth, $totalRevenueMonth);
        $categoriesExpense = $this->categoryService->findCategoryByUserIdAndType($this->session->userId, 'Despesas');
        $categoriesRevenue = $this->categoryService->findCategoryByUserIdAndType($this->session->userId, 'Receitas');

        $clients = $this->clientService->findClientsByUserId($this->session->userId) ?? [];
        $quotes = $this->quoteService->buscaOrcamentosServico($this->session->userId) ?? [];
        
        echo $this->template->rendenizar("home.html", 
        [
            'titulo' => 'Home',
            'cashBalanceMonth' => $cashBalanceMonth,
            'totalRevenueMonth' => $totalRevenueMonth,
            'totalExpenseMonth' => $totalExpenseMonth,
            'marginMonth' => $marginMonth,
            'quotes' => Helpers::attachRelated($quotes, $clients, 'id_cliente', 'id', 'client_name', 'nome'),
            'categoriesExpense' => $categoriesExpense,
            'categoriesRevenue' => $categoriesRevenue,
        ]);    
    }
}
