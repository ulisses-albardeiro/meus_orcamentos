<?php
namespace sistema\Controlador\Painel\Home;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clientes\ClientesInterface;
use sistema\Servicos\Financas\FinancasInterface;
use sistema\Servicos\Orcamentos\OrcamentosInterface;

class HomeControlador extends PainelControlador
{
    protected FinancasInterface $financasServico;
    protected OrcamentosInterface $orcamentoServico;
    protected ClientesInterface $clientesServico;

    public function __construct(FinancasInterface $financasServico, OrcamentosInterface $orcamentoServico, ClientesInterface $clientesServico)
    {
        parent::__construct();
        $this->financasServico = $financasServico;
        $this->orcamentoServico = $orcamentoServico;
        $this->clientesServico = $clientesServico;
    }


    public function listar() : void
    {
        $totalCaixaMesAtual = $this->financasServico->totalPeriodoCaixa(date('Y-m-01'), date('Y-m-d'), $this->usuario->usuarioId);
        $totalReceitaMesAtual = $this->financasServico->somaPeriodoReceitasUsuarioServico(date('Y-m-01'), date('Y-m-d'), $this->usuario->usuarioId);
        $totalDespesasMesAtual = $this->financasServico->somaPeriodoDespesasUsuarioServico(date('Y-m-01'), date('Y-m-d'), $this->usuario->usuarioId);
        $margemMes = $this->financasServico->calculaMargem($totalCaixaMesAtual, $totalReceitaMesAtual);

        $clientes = $this->clientesServico->buscaClientesPorIdUsuarioServico($this->usuario->usuarioId);
        $orcamentos = Helpers::colocarTodosNomesClientesPeloId($clientes, $this->orcamentoServico->buscaOrcamentosServico($this->usuario->usuarioId));

        (Helpers::colocarTodosNomesClientesPeloId($clientes, $orcamentos));
        echo $this->template->rendenizar("home.html", 
        [
            'titulo' => 'Home',
            'totalCaixaMesAtual' => $totalCaixaMesAtual,
            'totalReceitaMesAtual' => $totalReceitaMesAtual,
            'totalDespesasMesAtual' => $totalDespesasMesAtual,
            'margemDoMes' => $margemMes,
            'orcamentos' => $orcamentos,
            'linkAtivo' => 'active',
        ]);    
    }
}