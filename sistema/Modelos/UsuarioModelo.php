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
        $usuario = (new UsuarioModelo())->buscaPorUsuario($dados['usuario']);
        if (!$usuario or !password_verify($dados['senha'], $usuario->senha)) {
            $this->mensagem->mensagemErro("Dados incorretos!")->flash();
            Helpers::redirecionar('login');
        } elseif ($usuario->status == '0') {
            $this->mensagem->mensagemErro("Este usuário foi desativado! Se necessário, contacte um administrador.")->flash();
            Helpers::redirecionar('login');
        } else {
            (new Sessao)->criarSessao('usuarioId', $usuario->id);
            //salva o ultimo login
            $usuario->id = $usuario->id;
            $usuario->ultimo_login = date('Y-m-d H:i:s');
            $usuario->salvar();

            $this->mensagem->mensagemSucesso("Bem vindo, {$usuario->nome}")->flash();

            return true;
        }
    }
}
