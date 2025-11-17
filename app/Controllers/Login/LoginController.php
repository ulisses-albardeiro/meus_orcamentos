<?php

namespace App\Controllers\Login;

use App\Core\Controller;
use App\Core\Helpers;
use App\Services\Login\AuthInterface;

class LoginController extends Controller
{
    public function __construct(private AuthInterface $loginService)
    {
        parent::__construct('templates/views');
    }

    public function create(): void
    {
        if ($this->loginService->check()) {
            Helpers::redirect('home');
            return;
        }
        echo $this->template->render('login.html', []);
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!$this->loginService->attempt(...$data)) {
            $this->mensagem->mensagemErro("Dados incorretos")->flash();
            Helpers::redirect('login');
        }
        Helpers::redirect('home');
    }

    public function destroy(): void
    {
        $this->loginService->logout();
        Helpers::redirect('/');
    }
}
