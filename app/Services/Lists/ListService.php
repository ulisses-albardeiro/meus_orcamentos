<?php

namespace App\Services\Lists;

use App\Models\ListModel;

class ListService implements ListInterface
{
    public function __construct(private ListModel $listModel) {}

    public function registerList(array $data, int $clientId, int $userId, string $template, string $hash): bool
    {
        return $this->listModel->registerList($clientId, $data, $userId, $template, $hash);
    }

    public function destroyList(string $hash): bool
    {
        return $this->listModel->destroyList($hash);
    }

    public function findListsByUserId(int $userId): ?array
    {
        return $this->listModel->findListsByUserId($userId);
    }

    public function findListByHash(string $hash): array
    {
        $data = $this->listModel->findListByHash($hash)[0];

        $dataList = json_decode($data->lista_completa, true);
        $dataFull = array_merge((array) $data, $dataList);
        return $dataFull;
    }

    public function findListById(int $id_lista): ?array
    {
        $data = $this->listModel->findListById($id_lista)[0];
        $dataJson = json_decode($data->orcamento_completo, true);
        $dataFull = array_merge((array) $data, $dataJson);

        return $dataFull;
    }
}
