<?php

namespace app\Servicos\Finance;

use app\Nucleo\Modelo;

interface CategoryInterface
{
    public function findCategoryByName(string $name, string $type, int $userId): ?Modelo;
    public function findCategoryByUserId(int $userId): ?array;
    public function findCategoryByUserIdAndType(int $userId, string $type): ?array;
    public function createCategory(array $data, int $userId): bool;
    public function updateCategory(string $name, int $id): bool;
    public function destroyCategory(int $id): bool;
}
