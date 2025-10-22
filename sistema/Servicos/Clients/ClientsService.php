<?php

namespace sistema\Servicos\Clients;

use sistema\Modelos\ClientesModelo;

class ClientsService implements ClientsInterface
{
    protected ClientesModelo $clienteModelo;

    public function __construct(ClientesModelo $clienteModelo)
    {
        $this->clienteModelo = $clienteModelo;
    }

    public function findClientsByUserId(int $userId): ?array
    {
        return $this->clienteModelo->buscaClientesPorIdUsuario($userId);
    }

    public function registerClient(array $data, int $userId): ?int
    {
        return $this->clienteModelo->cadastraClientes($data, $userId);
    }

    public function destroyClient(int $clientId): bool
    {
        return $this->clienteModelo->excluirClientes($clientId);
    }

    public function updateClient(array $data, int $clientId): bool
    {
        return $this->clienteModelo->editarCliente($data, $clientId);
    }
}
