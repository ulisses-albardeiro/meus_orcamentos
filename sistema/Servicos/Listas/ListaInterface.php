<?php

namespace sistema\Servicos\Listas;

interface ListaInterface
{
    public function buscarListasServico(int $id_usuario): ?array;
    public function buscaListaPorHashServico(string $hash): array;
    public function buscaListaPorIdServico(int $id_lista): ?array;
    public function excluirListasServico(string $hash): bool;
    public function cadastrarListaServico(array $dados, int $id_cliente, int $id_usuario, string $modelo, string $hash): bool;
}
