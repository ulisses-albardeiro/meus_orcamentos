<?php

namespace App\Services\User;

use App\Core\Session;
use App\Models\UserModel;

class UserService implements UserInterface
{
    public function __construct(private UserModel $userModel, private Session $sessao) {}

    public function findUserById(int $id_usuario)
    {
        return $this->userModel->findUserById($id_usuario);
    }

    public function updateStatus(int $id, int $status): bool
    {
        if ($this->userModel->updateStatus($id, $status)) {
            $this->sessao->destroy();
            return true;
        }
        return false;
    }
}
