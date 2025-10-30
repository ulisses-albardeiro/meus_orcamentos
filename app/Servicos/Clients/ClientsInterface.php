<?php

namespace app\Servicos\Clients;

interface ClientsInterface
{
    public function findClientsByUserId(int $userId): ?array;
    public function registerClient(array $data, int $userId): ?int;
    public function destroyClient(int $clientId): bool;
    public function updateClient(array $data, int $clientId): bool;
}
