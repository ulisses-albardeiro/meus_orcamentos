<?php

namespace sistema\Servicos\Finance;

use sistema\Nucleo\Modelo;

interface CategoryInterface
{
    public function findCategoryByName(string $name, string $type, int $userId): ?Modelo;
    public function findCategoryByUserId(int $userId): ?array;
    public function findCategoryByUserIdAndType(int $userId, string $type): ?array;
    public function saveCategory(array $data, int $userId): bool;
    public function updateCategory(string $name, int $id): bool;
    public function destroyCategory(int $id): bool;
}
