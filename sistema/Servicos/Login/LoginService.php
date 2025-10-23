<?php

namespace sistema\Servicos\Login;

use sistema\Modelos\UserModel;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;
use sistema\Servicos\Login\AuthInterface;

class LoginService implements AuthInterface
{
    public function __construct(private UserModel $userModel, private Sessao $session) {}

    public function attempt(string $email, string $password): bool
    {
        
        if (!Helpers::validateEmail($email)) {
            return false;
        }

        $user = $this->userModel->findByEmail($email);

        if (empty($user)) {
            return false;
        }

        if (!password_verify($password, $user->senha)) {
            return false;
        }
        $this->session->criarSessao('userId', $user->id);

        return true;
    }

    public function check(): bool
    {
        return $this->session->checarSessao('userId');
    }

    public function logout(): void
    {
        $this->session->deletarSessao();
    }
}
