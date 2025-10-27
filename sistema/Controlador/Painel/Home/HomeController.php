<?php
namespace sistema\Controlador\Painel\Home;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clients\ClientsInterface;
use sistema\Servicos\Finance\DashboardInterface;
use sistema\Servicos\Orcamentos\OrcamentosInterface;

class HomeController extends PainelControlador
{
    public function __construct(
        private DashboardInterface $financeService, 
        private OrcamentosInterface $orcamentoServico, 
        private ClientsInterface $clientesServico)
    {
        parent::__construct();
    }

    public function index() : void
    {
        $totalCaixaMesAtual = $this->financeService->findCashBalanceByPeriod(date('Y-m-01'), date('Y-m-d'), $this->usuario->userId);
        $totalReceitaMesAtual = $this->financeService->sumRevenueByPeriod(date('Y-m-01'), date('Y-m-d'), $this->usuario->userId);
        $totalDespesasMesAtual = $this->financeService->sumExpensesByPeriod(date('Y-m-01'), date('Y-m-d'), $this->usuario->userId);
        $margemMes = $this->financeService->calculateMargin($totalCaixaMesAtual, $totalReceitaMesAtual);

        $clientes = $this->clientesServico->findClientsByUserId($this->usuario->userId);
        $orcamentos = Helpers::colocarTodosNomesClientesPeloId($clientes, $this->orcamentoServico->buscaOrcamentosServico($this->usuario->userId));

        (Helpers::colocarTodosNomesClientesPeloId($clientes, $orcamentos));
        echo $this->template->rendenizar("home.html", 
        [
            'titulo' => 'Home',
            'totalCaixaMesAtual' => $totalCaixaMesAtual,
            'totalReceitaMesAtual' => $totalReceitaMesAtual,
            'totalDespesasMesAtual' => $totalDespesasMesAtual,
            'margemDoMes' => $margemMes,
            'orcamentos' => $orcamentos,
        ]);    
    }
}