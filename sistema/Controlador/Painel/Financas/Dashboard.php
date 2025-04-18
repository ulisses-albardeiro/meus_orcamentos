<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\DespesaModelo;
use sistema\Modelos\ReceitaModelo;

class Dashboard extends PainelControlador
{
    public function listar(): void
    {
        $data_input = filter_input(INPUT_POST, "data", FILTER_DEFAULT);

        if ($data_input) {
            $data = date('Y-m-01', strtotime($data_input));
        } else {
            $data = date('Y-m-01');
        }

        echo $this->template->rendenizar(
            "financas/dashboard.html",
            [
                "categorias" => (new CategoriaModelo)->busca()->resultado(true),
                "receita_total" => $this->somarReceita($data, date('Y-m-t', strtotime($data))),
                "despesas_total" => $this->somarDespesa($data, date('Y-m-t', strtotime($data))),
                "data" => substr($data, 0, 7),
                "despesas_categoria" => $this->despesasPorCategoria($data, date('Y-m-t', strtotime($data))),
            ]
        );
    }

    private function somarReceita(string $data, string $data_final): float
    {
        $receitas = (new ReceitaModelo)->busca('dt_receita >= :inicio AND dt_receita <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$this->usuario->id}")->resultado(true);
        if (empty($receitas)) {
            return 0;
        }
        return array_sum(array_column($receitas, 'valor')) / 100;
    }

    private function somarDespesa(string $data, $data_final): float
    {
        $despesa = (new DespesaModelo)->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$this->usuario->id}")->resultado(true);
        if (empty($despesa)) {
            return 0;
        }
        return array_sum(array_column($despesa, 'valor')) / 100;
    }

    private function despesasPorCategoria(string $data, string $data_final): array
    {
        $despesas = (new DespesaModelo)->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$this->usuario->id}")->resultado(true);

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

    private function getNomeCategoria(array $despesas) : array
    { 
        $categorias = (new CategoriaModelo)->busca()->resultado(true);

        $despesas = array_map(function($despesa) use ($categorias){
            foreach($categorias as $categoria){
                if ($despesa->id_categoria == $categoria->id) {
                    $despesa->categoria = $categoria->nome;
                }
            }
            return $despesa;
        }, $despesas);

        return $despesas;
    }
}

