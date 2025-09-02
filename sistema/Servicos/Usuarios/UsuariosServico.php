<?php

namespace sistema\Servicos\Usuarios;

use sistema\Modelos\UsuarioModelo;

class UsuariosServico implements UsuariosInterface
{
    protected UsuarioModelo $usuarioModelo;

    public function __construct(UsuarioModelo $usuarioModelo)
    {
        $this->usuarioModelo = $usuarioModelo;
    }

    public function buscaUsuariosPorIdServico(int $id_usuario)
    {
        return $this->usuarioModelo->buscaUsuarioPorId($id_usuario);
    }
}