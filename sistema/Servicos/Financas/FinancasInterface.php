<?php
namespace sistema\Servicos\Financas;

interface FinancasInterface
{
    public function somaPeriodoReceitasUsuarioServico(string $dataInicial, string $dataFinal, int $idUsuario): float|int;
    public function somaPeriodoDespesasUsuarioServico(string $dataInicial, string $dataFinal, int $idUsuario): float|int;
    public function totalPeriodoCaixa(string $dataInicial, string $dataFinal, int $idUsuario): float|int;
    public function calculaMargem(float|int $lucro, float|int $receita): float|int;
}