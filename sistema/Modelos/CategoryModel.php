<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;

class CategoryModel extends Modelo
{
    public function __construct()
    {
        parent::__construct("categorias");
    }

    public function getCategorias(int $id_usuario): array
    {
        $categorias = $this->busca("id_usuario = {$id_usuario}")->ordem("id DESC")->resultado(true) ?? [];
        return $categorias;
    }

    public function destroyCategory(int $id): bool
    {
        return $this->apagar("id = {$id}");
    }
    public function saveCategory(array $data, int $userId): bool
    {
        $this->nome = $data['nome'];
        $this->tipo = $data['tipo'];
        $this->dt_hr_criacao = date('Y-m-d H:i:s');
        $this->id_usuario = $userId;
        return $this->salvar();
    }

    public function updateCategory(string $name, int $id): bool
    {
        $this->id = $id;
        $this->nome = $name;

        return $this->salvar();
    }

    public function findCategoryByName(string $name, string $type, int $userId): ?Modelo
    {
        return $this->busca(
            "nome = :n AND tipo = :t AND id_usuario = :id",
            ":n={$name}&:t={$type}&:id={$userId}"
        )->resultado();
    }

    public function findCategoryByUserId(int $userId): ?array
    {
        return $this->busca(
            "id_usuario = {$userId}"
        )->resultado(true);
    }

    public function findCategoryByUserIdAndType(int $userId, string $type): ?array
    {
        return $this->busca(
            "id_usuario = {$userId} AND tipo = '{$type}'"
        )->resultado(true);
    }
}
