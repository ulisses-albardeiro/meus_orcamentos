<?php

namespace sistema\Servicos\Finance;

use sistema\Modelos\CategoryModel;
use sistema\Nucleo\Modelo;

class CategoryService implements CategoryInterface
{
    public function __construct(private CategoryModel $categoryModel){}

    public function findCategoryByName(string $name, string $type, int $userId): ?Modelo
    {
        return $this->categoryModel->findCategoryByName($name, $type, $userId);
    }

    public function saveCategory(array $data, int $userId): bool
    {
        return $this->categoryModel->saveCategory($data, $userId);
    }    

    public function updateCategory(string $name, int $id): bool
    {
        return $this->categoryModel->updateCategory($name, $id);
    }

    public function destroyCategory(int $id): bool
    {
        return $this->categoryModel->destroyCategory($id);
    }

    public function findCategoryByUserId(int $userId): ?array
    {
        return $this->categoryModel->findCategoryByUserId($userId);
    }
}
