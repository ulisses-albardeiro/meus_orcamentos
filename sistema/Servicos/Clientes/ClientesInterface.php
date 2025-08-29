<?php

namespace sistema\Servicos\Clientes;

interface ClientesInterface
{
    public function buscaClientesPorIdUsuarioServico(int $id_usuario): ?array;
    public function cadastraClientesServico(array $dados, int $id_usuario): ?int;
    public function excluirClientesServico(int $id_cliente): bool;
    public function editarClientesServico(array $dados, int $id_cliente): bool;
}