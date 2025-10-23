<?php
namespace sistema\Controlador\Painel\Home;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clients\ClientsInterface;
use sistema\Servicos\Financas\FinancasInterface;
use sistema\Servicos\Orcamentos\OrcamentosInterface;

class HomeController extends PainelControlador
{
    protected FinancasInterface $financasServico;
    protected OrcamentosInterface $orcamentoServico;
    protected ClientsInterface $clientesServico;

    public function __construct(FinancasInterface $financasServico, OrcamentosInterface $orcamentoServico, ClientsInterface $clientesServico)
    {
        parent::__construct();
        $this->financasServico = $financasServico;
        $this->orcamentoServico = $orcamentoServico;
        $this->clientesServico = $clientesServico;
    }


    public function index() : void
    {
        $totalCaixaMesAtual = $this->financasServico->totalPeriodoCaixa(date('Y-m-01'), date('Y-m-d'), $this->usuario->userId);
        $totalReceitaMesAtual = $this->financasServico->somaPeriodoReceitasUsuarioServico(date('Y-m-01'), date('Y-m-d'), $this->usuario->userId);
        $totalDespesasMesAtual = $this->financasServico->somaPeriodoDespesasUsuarioServico(date('Y-m-01'), date('Y-m-d'), $this->usuario->userId);
        $margemMes = $this->financasServico->calculaMargem($totalCaixaMesAtual, $totalReceitaMesAtual);

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