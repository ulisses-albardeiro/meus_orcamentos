<?php

namespace App\Models;

use App\Core\Modelo;

class UserModel extends Modelo
{
    public function __construct()
    {
        parent::__construct("usuarios");
    }

    public function buscaUsuarioPorId(int $id_usuario): Modelo
    {
        return $this->busca("id = {$id_usuario}")->resultado();
    }

    public function apagarUsuario(int $idUsuario) : bool
    {
        return $this->apagar("id = {$idUsuario}");    
    }

    public function saveToken(int $id, string $token): bool
    {
        $this->id = $id;
        $this->token = $token;
        $this->dt_hr_token = date('Y-m-d H:i:s');
        return $this->salvar();
    }

    public function updatePassword(int $id, string $password): bool
    {
        $this->id = $id;
        $this->senha = $password;
        $this->token = null;
        $this->dt_hr_token = null;

        return $this->salvar();
    }

    public function findByEmail(string $email): ?Modelo
    {
        return $this->busca("email = :e", ":e={$email}")->resultado();
    }
}
