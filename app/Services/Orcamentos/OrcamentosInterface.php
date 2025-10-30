<?php

namespace App\Services\Orcamentos;

interface OrcamentosInterface
{
    public function calcularTotalOrcamento(array $dados): int;
    public function separarDadosUsuario(array $dados): array;
    public function separarDadosCliente(array $dados): array;
    public function processarItensParaView(array $dados): array;
    public function converterValorParaFloat(string $valorFormatado): float;
    public function buscaOrcamentosServico(int $idUsuario): ?array;
    public function buscaOrcamentoPorHashServico(string $hash): ?array;
    public function buscaOrcamentoPorIdServico(int $idOrcamento): ?array;
    public function excluirOrcamentoServico(string $hash): bool;
}
