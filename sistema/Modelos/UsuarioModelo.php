<?php

namespace sistema\Modelos;

use sistema\Nucleo\Modelo;
use sistema\Nucleo\Sessao;
use sistema\Nucleo\Helpers;

class UsuarioModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct("usuarios");
    }

    public function buscaPorUsuario(string $email_or_cpf): ?UsuarioModelo
    {
        if (str_contains($email_or_cpf, '@')) {
            $busca_email = $this->busca("email = :e", ":e={$email_or_cpf}");

            return $busca_email->resultado();
        } else {
            $busca_cpf = $this->busca("cpf = :c", ":c={$email_or_cpf}");

            return $busca_cpf->resultado();
        }
    }

    public function login(array $dados, int $nivel = 1)
    {
        $usuario = $this->buscaPorUsuario($dados['usuario']);

        if (!$usuario or !password_verify($dados['senha'], $usuario->senha)) {
            $this->mensagem->mensagemErro("Dados incorretos!")->flash();
            Helpers::redirecionar('login');
        } elseif ($usuario->status == '0') {
            $this->mensagem->mensagemErro("Este usuário foi desativado! Se necessário, contacte o suporte.")->flash();
            Helpers::redirecionar('login');
        } else {
            (new Sessao)->criarSessao('usuarioId', $usuario->id);
            //salva o ultimo login
            $usuario->id = $usuario->id;
            $usuario->ultimo_login = date('Y-m-d H:i:s');
            $usuario->salvar();

            return true;
        }
    }

    public function buscaUsuarioPorId(int $id_usuario): Modelo
    {
        return $this->busca("id = {$id_usuario}")->resultado();
    }

    public function apagarRegistrosPorUsuario(int $id_usuario)
    {
        $sucesso = true;

        $tabelaUsuario = new self();
        if (!$tabelaUsuario->apagar("id = {$id_usuario}")) {
            $sucesso = false;
        }

        $tabelaPosts = new Modelo('posts');
        if (!$tabelaPosts->apagar("id_usuario = {$id_usuario}")) {
            $sucesso = false;
        }

        $tabelaComentarios = new Modelo('comentarios');
        if (!$tabelaComentarios->apagar("id_usuario = {$id_usuario}")) {
            $sucesso = false;
        }

        return $sucesso;
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
}
