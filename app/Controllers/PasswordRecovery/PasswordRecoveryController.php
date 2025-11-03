<?php

namespace App\Controllers\PasswordRecovery;

use App\Core\Controller;
use App\Core\Helpers;
use App\Services\PasswordRecovery\PasswordRecoveryInterface;

class PasswordRecoveryController extends Controller
{
    public function __construct(private PasswordRecoveryInterface $passwordRecoveryService)
    {
        parent::__construct('templates/views');
        $this->passwordRecoveryService = $passwordRecoveryService;
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            'password-recovery/email-form.html',
            []
        );
    }

    public function store(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $this->mensagem->mensagemAtencao("Por favor, informe um email válido.")->flash();
            Helpers::redirecionar('password-recovery');
            return;
        }

        if ($this->passwordRecoveryService->initiateRecovery($email)) {
            $this->mensagem->mensagemSucesso("Caso o email exista em nossa base, um link de recuperação de senha sera enviado para ele.")->flash();
        } else {
            $this->mensagem->mensagemErro("Houve um erro inesperado. Tente novamente ou fale com o suporte")->flash();
        }

        Helpers::redirecionar('login');
    }

    public function create(string $token): void
    {
        $validToken = $this->passwordRecoveryService->validateToken($token);

        if (!$validToken) {
            $this->mensagem->mensagemAtencao("Solicitação inválida ou expirada. Faça uma nova solicitação.")->flash();
            Helpers::redirecionar('login');
            return;
        }

        echo $this->template->rendenizar(
            'password-recovery/new-password.html',
            [
                'token' => $token
            ]
        );
    }

    public function update(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!$this->passwordRecoveryService->validateNewPassword($data['password1'], $data['password2'])) {
            $this->mensagem->mensagemAtencao("Dados inválidos ou incompletos.")->flash();
            Helpers::redirecionar('login');
            return;
        }

        if (!$this->passwordRecoveryService->saveNewPassword($data['token'], $data['password1'])) {
            $this->mensagem->mensagemErro("Erro ao salvar a nova senha ou token inválido.")->flash();
            Helpers::redirecionar('login');
            return;
        }
        $this->mensagem->mensagemSucesso("Senha alterada com sucesso")->flash();
        Helpers::redirecionar('login');
    }
}
