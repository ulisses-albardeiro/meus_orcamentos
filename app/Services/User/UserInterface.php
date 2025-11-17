<?php

namespace App\Services\User;

interface UserInterface
{
    public function findUserById(int $id_usuario);
    public function updateStatus(int $id, int $status): bool;
}