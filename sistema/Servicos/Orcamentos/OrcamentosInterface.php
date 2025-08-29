<?php

namespace sistema\Servicos\Orcamentos;

interface OrcamentosInterface
{
    public function calcularTotalOrcamento(array $dados): int;
    public function separarDadosUsuario(array $dados): array;
    public function separarDadosCliente(array $dados): array;
    public function processarItensParaView(array $dados): array;
    public function converterValorParaFloat(string $valorFormatado): float;
}
