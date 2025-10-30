<?php

namespace App\Servicos\Usuarios;

interface UsuariosInterface
{
    public function buscaUsuariosPorIdServico(int $id_usuario);
}