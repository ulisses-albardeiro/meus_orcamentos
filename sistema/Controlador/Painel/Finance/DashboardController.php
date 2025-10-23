<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\Finance\Servicos\ServicosDashboard;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\ExpenseModel;
use sistema\Modelos\RevenueModel;

class DashboardController extends PainelControlador
{
    private object $servico;

    public function __construct()
    {
        parent::__construct();
        $this->servico = new ServicosDashboard;
    }

    public function index(?string $date = null): void
    {
        $data = $date ? date('Y-m-01', strtotime($date)) :  date('Y-m-01');
        
        echo $this->servico->somarReceita($data, date('Y-m-t', strtotime($data)), $this->usuario->userId);
        echo $this->template->rendenizar("financas/dashboard.html",
            [
                "categorias" => (new CategoriaModelo)->busca()->resultado(true),
                "receita_total" => $this->servico->somarReceita($data, date('Y-m-t', strtotime($data)), $this->usuario->userId),
                "despesas_total" => $this->servico->somarDespesa($data, date('Y-m-t', strtotime($data)), $this->usuario->userId),
                "data" => substr($data, 0, 7),
                "despesas_categoria" => $this->servico->despesasPorCategoria($data, date('Y-m-t', strtotime($data)), $this->usuario->userId),
                "dados_grafico_trimestral" => $this->servico->getDadosGraficoTrimestral($data, $this->usuario->userId),
                "titulo" => "Finanças"
            ]
        );
    }
}
