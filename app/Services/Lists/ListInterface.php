<?php

namespace App\Services\Lists;

interface ListInterface
{
    public function findListsByUserId(int $userId): ?array;
    public function findListByHash(string $hash): array;
    public function findListById(int $listId): ?array;
    public function destroyList(string $hash): bool;
    public function registerList(array $data, int $clientId, int $userId, string $template, string $hash): bool;
}
