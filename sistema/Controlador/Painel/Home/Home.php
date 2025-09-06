<?php
namespace sistema\Controlador\Painel\Home;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Servicos\Financas\FinancasInterface;

class Home extends PainelControlador
{
    protected FinancasInterface $financasServico;

    public function __construct(FinancasInterface $financasServico)
    {
        parent::__construct();
        $this->financasServico = $financasServico;
    }


    public function listar() : void
    {
        $totalCaixaMesAtual = $this->financasServico->totalPeriodoCaixa(date('Y-m-01'), date('Y-m-d'), $this->usuario->usuarioId);
        $totalReceitaMesAtual = $this->financasServico->somaPeriodoReceitasUsuarioServico(date('Y-m-01'), date('Y-m-d'), $this->usuario->usuarioId);
        $totalDespesasMesAtual = $this->financasServico->somaPeriodoDespesasUsuarioServico(date('Y-m-01'), date('Y-m-d'), $this->usuario->usuarioId);
        $margemMes = $this->financasServico->calculaMargem($totalCaixaMesAtual, $totalReceitaMesAtual);


        echo $this->template->rendenizar("home.html", 
        [
            'titulo' => 'Home',
            'totalCaixaMesAtual' => $totalCaixaMesAtual,
            'totalReceitaMesAtual' => $totalReceitaMesAtual,
            'totalDespesasMesAtual' => $totalDespesasMesAtual,
            'margemDoMes' => $margemMes,
        ]);    
    }
}