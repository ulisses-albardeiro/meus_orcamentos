<?php

namespace App\Services\Clients;

use App\Models\ClientsModel;

class ClientsService implements ClientsInterface
{
    protected ClientsModel $clientModel;

    public function __construct(ClientsModel $clientModel)
    {
        $this->clientModel = $clientModel;
    }

    public function findClientsByUserId(int $userId): ?array
    {
        return $this->clientModel->findClientsByUserId($userId);
    }

    public function registerClient(array $data, int $userId): ?int
    {
        return $this->clientModel->registerClient($data, $userId);
    }

    public function destroyClient(int $clientId): bool
    {
        return $this->clientModel->destroyClient($clientId);
    }

    public function updateClient(array $data, int $clientId): bool
    {
        return $this->clientModel->updateClient($data, $clientId);
    }
}
