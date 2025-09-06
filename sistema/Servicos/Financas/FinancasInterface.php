<?php
namespace sistema\Servicos\Financas;

interface FinancasInterface
{
    public function somaPeriodoReceitasUsuarioServico(string $dataInicial, string $dataFinal, int $idUsuario): int;
    public function somaPeriodoDespesasUsuarioServico(string $dataInicial, string $dataFinal, int $idUsuario): int;
    public function totalPeriodoCaixa(string $dataInicial, string $dataFinal, int $idUsuario): int;
    public function calculaMargem(int $lucro, int $receita): int;
}