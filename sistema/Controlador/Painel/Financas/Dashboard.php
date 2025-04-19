<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\Financas\Servicos\ServicosDashboard;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\DespesaModelo;
use sistema\Modelos\ReceitaModelo;

class Dashboard extends PainelControlador
{
    private object $servico;

    public function __construct()
    {
        parent::__construct();
        $this->servico = new ServicosDashboard;
    }

    public function listar(): void
    {
        $data_input = filter_input(INPUT_POST, "data", FILTER_DEFAULT);

        $data = $data_input ? date('Y-m-01', strtotime($data_input)) :  date('Y-m-01');
        
        echo $this->template->rendenizar("financas/dashboard.html",
            [
                "categorias" => (new CategoriaModelo)->busca()->resultado(true),
                "receita_total" => $this->servico->somarReceita($data, date('Y-m-t', strtotime($data)), $this->usuario->id),
                "despesas_total" => $this->servico->somarDespesa($data, date('Y-m-t', strtotime($data)), $this->usuario->id),
                "data" => substr($data, 0, 7),
                "despesas_categoria" => $this->servico->despesasPorCategoria($data, date('Y-m-t', strtotime($data)), $this->usuario->id),
                "dados_grafico_trimestral" => $this->servico->getDadosGraficoTrimestral($data, $this->usuario->id)
            ]
        );
    }
}
