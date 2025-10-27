<?php

namespace sistema\Servicos\Finance;

interface RevenueInterface
{
    public function updateRevenue(array $data, int $id): bool;
    public function destroyRevenue(int $id): bool;
    public function createRevenue(array $data, int $userId): bool;
    public function findRevenueByUserId(int $userId): array;
}