<?php

namespace App\Services\Usuarios;

interface UsuariosInterface
{
    public function buscaUsuariosPorIdServico(int $id_usuario);
}