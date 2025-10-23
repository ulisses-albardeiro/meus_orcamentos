<?php

namespace sistema\Servicos\Usuarios;

use sistema\Modelos\UserModel;

class UsuariosServico implements UsuariosInterface
{
    protected UserModel $usuarioModelo;

    public function __construct(UserModel $usuarioModelo)
    {
        $this->usuarioModelo = $usuarioModelo;
    }

    public function buscaUsuariosPorIdServico(int $id_usuario)
    {
        return $this->usuarioModelo->buscaUsuarioPorId($id_usuario);
    }
}