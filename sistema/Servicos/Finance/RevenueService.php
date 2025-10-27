<?php

namespace sistema\Servicos\Finance;

use sistema\Modelos\RevenueModel;

class RevenueService implements RevenueInterface
{
    public function __construct(private RevenueModel $revenueModel){}

    public function updateRevenue(array $data, int $id): bool
    {
        return $this->revenueModel->updateRevenue($data, $id);
    }

    public function destroyRevenue(int $id): bool
    {
        return $this->revenueModel->destroyRevenue($id);
    }

    public function createRevenue(array $data, int $userId): bool
    {
        return $this->revenueModel->createRevenue($data, $userId);
    }

    public function findRevenueByUserId(int $userId): array
    {
        return $this->revenueModel->findRevenueByUserId($userId) ?? [];
    }
}