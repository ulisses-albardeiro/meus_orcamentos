<?php

namespace App\Services\Usuarios;

use App\Core\Sessao;
use App\Models\UserModel;

class UsuariosServico implements UsuariosInterface
{
    public function __construct(private UserModel $userModel, private Sessao $sessao) {}

    public function buscaUsuariosPorIdServico(int $id_usuario)
    {
        return $this->userModel->buscaUsuarioPorId($id_usuario);
    }

    public function updateStatus(int $id, int $status): bool
    {
        if ($this->userModel->updateStatus($id, $status)) {
            $this->sessao->deletarSessao();
            return true;
        }
        return false;
    }
}
