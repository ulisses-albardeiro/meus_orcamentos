<?php

namespace sistema\Servicos\Finance;

use sistema\Modelos\RevenueModel;
use sistema\Nucleo\Helpers;

class RevenueService implements RevenueInterface
{
    public function __construct(private RevenueModel $revenueModel){}

    public function updateRevenue(array $data, int $id): bool
    {
        $data['revenue'] = Helpers::ReaisToCentsSingle($data['revenue']);
        return $this->revenueModel->updateRevenue($data, $id);
    }

    public function destroyRevenue(int $id): bool
    {
        return $this->revenueModel->destroyRevenue($id);
    }

    public function createRevenue(array $data, int $userId): bool
    {
        $data['revenue'] = Helpers::ReaisToCentsSingle($data['revenue']);
        return $this->revenueModel->createRevenue($data, $userId);
    }

    public function findRevenueByUserId(int $userId): array
    {
        $revenues = $this->revenueModel->findRevenueByUserId($userId) ?? [];
        return Helpers::centsToReais($revenues, 'valor');
    }
}