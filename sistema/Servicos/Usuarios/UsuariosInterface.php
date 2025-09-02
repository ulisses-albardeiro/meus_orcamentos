<?php

namespace sistema\Servicos\Usuarios;

interface UsuariosInterface
{
    public function buscaUsuariosPorIdServico(int $id_usuario);
}