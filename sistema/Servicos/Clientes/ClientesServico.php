<?php

namespace sistema\Servicos\Clientes;

use sistema\Modelos\ClientesModelo;

class ClientesServico implements ClientesInterface
{
    protected ClientesModelo $clienteModelo;

    public function __construct(ClientesModelo $clienteModelo)
    {
        $this->clienteModelo = $clienteModelo;
    }

    public function buscaClientesServico(): ?array
    {
        return $this->clienteModelo->buscaClientes();
    }

    public function cadastraClientesServico(array $dados): ?int
    {
        return $this->clienteModelo->cadastraClientes($dados);
    }

    public function excluirClientesServico(int $id_cliente): bool
    {
        return $this->clienteModelo->excluirClientes($id_cliente);
    }

    public function editarClientesServico(array $dados, int $id_cliente): bool
    {
        return $this->clienteModelo->editarCliente($dados, $id_cliente);
    }
}