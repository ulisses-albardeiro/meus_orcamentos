<?php

namespace app\Servicos\Usuarios;

interface UsuariosInterface
{
    public function buscaUsuariosPorIdServico(int $id_usuario);
}