<?php

namespace App\Services\Finance;

use App\Models\CategoryModel;
use App\Core\Model;

class CategoryService implements CategoryInterface
{
    public function __construct(private CategoryModel $categoryModel){}

    public function findCategoryByName(string $name, string $type, int $userId): ?Model
    {
        return $this->categoryModel->findCategoryByName($name, $type, $userId);
    }

    public function createCategory(array $data, int $userId): bool
    {
        return $this->categoryModel->createCategory($data, $userId);
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

    public function findCategoryByUserIdAndType(int $userId, string $type): ?array
    {
        return $this->categoryModel->findCategoryByUserIdAndType($userId, $type);
    }
}
