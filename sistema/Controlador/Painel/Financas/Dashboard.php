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

        $data_final = date('Y-m-t', strtotime($data));

        echo $this->template->rendenizar(
            "financas/dashboard.html",
            [
                "categorias" => (new CategoriaModelo)->busca()->resultado(true),
                "receita_total" => $this->somarReceita($data, $data_final),
                "despesas_total" => $this->somarDespesa($data, $data_final),
                "data" => substr($data, 0, 7)
            ]
        );
    }

    private function somarReceita(string $data, string $data_final): int
    {
        $receitas = (new ReceitaModelo)->busca('dt_receita >= :inicio AND dt_receita <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$this->usuario->id}")->resultado(true);
        if (empty($receitas)) {
            return 0;
        }
        return array_sum(array_column($receitas, 'valor')) / 100;
    }

    private function somarDespesa(string $data, $data_final): int
    {
        $despesa = (new DespesaModelo)->busca('dt_despesa >= :inicio AND dt_despesa <= :fim AND id_usuario = :id', ":inicio={$data}&:fim={$data_final}&:id={$this->usuario->id}")->resultado(true);
        if (empty($despesa)) {
            return 0;
        }
        return array_sum(array_column($despesa, 'valor')) / 100;
    }
}
