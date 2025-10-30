<?php

namespace App\Controlador\Login;

use App\Nucleo\Controlador;
use App\Nucleo\Helpers;
use App\Servicos\Login\AuthInterface;

class LoginController extends Controlador
{
    public function __construct(private AuthInterface $loginService)
    {
        parent::__construct('templates/views');
    }

    public function create(): void
    {
        if ($this->loginService->check()) {
            Helpers::redirecionar('home');
            return;
        }
        echo $this->template->rendenizar('login.html', []);
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!$this->loginService->attempt(...$data)) {
            $this->mensagem->mensagemErro("Dados incorretos")->flash();
            Helpers::redirecionar('login');
        }
        Helpers::redirecionar('home');
    }

    public function destroy(): void
    {
        $this->loginService->logout();
        Helpers::redirecionar('/');
    }
}
