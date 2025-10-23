<?php

namespace sistema\Servicos\Financas;

use sistema\Modelos\DespesaModelo;
use sistema\Modelos\ReceitaModelo;

class FinancasServico implements FinancasInterface
{
    protected ReceitaModelo $receitaModelo;
    protected DespesaModelo $despesaModelo;

    public function __construct(ReceitaModelo $receitaModelo, DespesaModelo $despesaModelo)
    {
        $this->receitaModelo = $receitaModelo;
        $this->despesaModelo = $despesaModelo;
    }

    public function somaPeriodoReceitasUsuarioServico(string $dataInicial, string $dataFinal, int $idUsuario): float|int
    {
        $receitasDoMes = $this->receitaModelo->buscaReceitasPorData($dataInicial, $dataFinal, $idUsuario);

        if (empty($receitasDoMes)) {
            return 0;
        }

        $totalReceitas = array_sum(array_column($receitasDoMes, 'valor'));
        return $totalReceitas/100;
    }

    public function somaPeriodoDespesasUsuarioServico(string $dataInicial, string $dataFinal, int $idUsuario): float|int
    {
        $despesasDoMes = $this->despesaModelo->buscaDespesasPorData($dataInicial, $dataFinal, $idUsuario);

        if (empty($despesasDoMes)) {
            return 0;
        }

        $totalDespesas = array_sum(array_column($despesasDoMes, 'valor'));

        return $totalDespesas/100;
    }

    public function totalPeriodoCaixa(string $dataInicial, string $dataFinal, int $idUsuario): float|int
    {
        $totalDespesas = $this->somaPeriodoDespesasUsuarioServico($dataInicial, $dataFinal, $idUsuario);
        $totalReceitas = $this->somaPeriodoReceitasUsuarioServico($dataInicial, $dataFinal, $idUsuario);

        $caixa = $totalReceitas - $totalDespesas;

        return $caixa;
    }

    public function calculaMargem(float|int $lucro, float|int $receita): float|int
    {
        if (empty($receita)) {
            return 0;
        }

        $margem = ($lucro / $receita) * 100;
        return $margem;
    }
}
