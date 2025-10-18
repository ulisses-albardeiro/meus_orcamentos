<?php

namespace sistema\Controlador\Painel\Finance\Servicos;

use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\DespesaModelo;
use sistema\Modelos\ReceitaModelo;

class ServicosDashboard
{
    public function somarReceita(string $data, string $data_final, int $id_usuario): float
    {
        $receitas = (new ReceitaModelo)->getReceitasPorData($data, $data_final, $id_usuario);
        if (empty($receitas)) {
            return 0;
        }
        return array_sum(array_column($receitas, 'valor')) / 100;
    }

    public function somarDespesa(string $data, string $data_final, int $id_usuario): float
    {
        $despesa = (new DespesaModelo)->getDespesasPorData($data, $data_final, $id_usuario);
        if (empty($despesa)) {
            return 0;
        }
        return array_sum(array_column($despesa, 'valor')) / 100;
    }

    public function despesasPorCategoria(string $data, string $data_final, int $id_usuario): array
    {
        $despesas = (new DespesaModelo)->getDespesasPorCategoria($data, $data_final, $id_usuario);

        if (empty($despesas)) {
            return [];
        }

        $despesas = $this->getNomeCategoria($despesas);

        $categorias = [];

        foreach ($despesas as $despesa) {
            $nome_categoria = $despesa->categoria ?? 'Outros';
            $valor = $despesa->valor / 100;
            if (!isset($categorias[$nome_categoria])) {
                $categorias[$nome_categoria] = 0;
            }
            $categorias[$nome_categoria] += $valor;
        }

        return $categorias;
    }

    private function getNomeCategoria(array $despesas): array
    {
        $categorias = (new CategoriaModelo)->busca()->resultado(true);

        $despesas = array_map(function ($despesa) use ($categorias) {
            foreach ($categorias as $categoria) {
                if ($despesa->id_categoria == $categoria->id) {
                    $despesa->categoria = $categoria->nome;
                }
            }
            return $despesa;
        }, $despesas);

        return $despesas;
    }

    private function getReceitaTrimestral(string $data_fim, int $id_usuario): array
    {
        $data_fim = date('Y-m-t', strtotime($data_fim));
        $data_inicio = date('Y-m-1', strtotime('first day of -2 months', strtotime($data_fim)));
        $receita_trimestral = (new ReceitaModelo)->getReceitaTrimestral($data_inicio, $data_fim, $id_usuario);

        return $receita_trimestral;
    }

    private function getDespesaTrimestral(string $data_fim, int $id_usuario): array
    {
        $data_fim = date('Y-m-t', strtotime($data_fim));
        $data_inicio = date('Y-m-01', strtotime('first day of -2 months', strtotime($data_fim)));
        $despesa_trimestral = (new DespesaModelo)->getDespesaTrimestral($data_inicio, $data_fim, $id_usuario);

        return $despesa_trimestral ?? [];
    }

    public function getDadosGraficoTrimestral(string $data_fim, int $id_usuario): array
    {
        $receitas = $this->getReceitaTrimestral($data_fim, $id_usuario);
        $despesas = $this->getDespesaTrimestral($data_fim, $id_usuario);

        $meses_exibidos = [
            date('Y-m', strtotime('first day of -2 months', strtotime($data_fim))), 
            date('Y-m', strtotime('first day of -1 month', strtotime($data_fim))),  
            date('Y-m', strtotime($data_fim))                                       
        ];

        $receitas_agrupadas = [];
        foreach ($receitas as $receita) {
            $mes = date('Y-m', strtotime($receita->dt_receita));
            $receitas_agrupadas[$mes] = ($receitas_agrupadas[$mes] ?? 0) + ($receita->valor/100);
        }

        $despesas_agrupadas = [];
        foreach ($despesas as $despesa) {
            $mes = date('Y-m', strtotime($despesa->dt_despesa));
            $despesas_agrupadas[$mes] = ($despesas_agrupadas[$mes] ?? 0) + ($despesa->valor/100);
        }

        $meses_traduzidos = [
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
        $valores_receitas = [];
        $valores_despesas = [];

        foreach ($meses_exibidos as $mes) {
            $mesNumero = explode('-', $mes)[1];
            $labels[] = $meses_traduzidos[$mesNumero];
            $valores_receitas[] = $receitas_agrupadas[$mes] ?? 0;
            $valores_despesas[] = $despesas_agrupadas[$mes] ?? 0;
        }

        return [
            'labels' => $labels,
            'receitas' => $valores_receitas,
            'despesas' => $valores_despesas
        ];
    }
}
