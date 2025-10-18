<?php

namespace sistema\Servicos\PasswordRecovery;

use sistema\Modelos\UsuarioModelo;
use sistema\Suporte\Email;
use sistema\Suporte\Template;

class PasswordRecoveryService implements PasswordRecoveryInterface
{
    protected UsuarioModelo $userModel;
    protected string $token;
    protected Template $template;

    public function __construct(UsuarioModelo $userModel, Template $template)
    {
        $this->userModel = $userModel;
        $this->template = $template;
        $this->token = hash('sha256', random_bytes(64));
    }


    public function initiateRecovery(string $email): bool
    {
        $userData = $this->checkEmail($email);
        if ($userData === null) {
            return true;
        }

        if ($this->saveToken($userData->id, $this->token) && $this->sendRecoveryEmail($email, $userData->nome)) {
            return true;
        }
        return false;
    }

    private function checkEmail(string $email): ?object
    {
        $verification = $this->userModel->busca("email = '{$email}'")->resultado();
        return $verification;
    }

    private function saveToken(int $id, string $token): bool
    {
        $user = $this->userModel;
        return $user->saveToken($id, $token);
    }

    private function sendRecoveryEmail(string $email, string $name): bool
    {
        $emailBody = $this->templateEmail($this->token);

        $mailer = (new Email(HOST_EMAIL, EMAIL_USER, EMAIL_PASSWORD, EMAIL_PORT));
        $mailer->criar('Recuperação de senha', $emailBody, $email, $name);

        if ($mailer->enviar(EMAIL_USER, 'Recuperação de senha - Meus Orçamentos (Não responda)')) {

            return true;
        }
        return false;
    }

    private function templateEmail(string $token): string
    {
        return $this->template->rendenizar(
            '/views/password-recovery/email-password-recovery.html',
            [
                'token' => $token
            ]
        );
    }

    public function validateToken(?string $token = null): ?string
    {
        if (!$token) {
            return null;
        }

        $user = $this->checkTokenExistence($token);

        if ($user) {
            return $this->checkTokenValidity($user) ? $token : null;
        }

        return null;
    }

    private function checkTokenExistence(string $token): ?object
    {
        $user = $this->userModel->busca("token = '{$token}'")->resultado();
        return $user;
    }

    private function checkTokenValidity(object $user): bool
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $tokenTimestamp = strtotime($user->dt_hr_token);
        $difference = $now - $tokenTimestamp;
        $timeInHours = round($difference / 3600);

        if ($timeInHours >= 2) {
            return false;
        }
        return true;
    }

    public function saveNewPassword(string $token, string $newPassword): bool
    {
        $userData = $this->userModel->busca("token = '{$token}'")->resultado();

        if (!$userData) {
            return false;
        }

        $user = $this->userModel;
        return $user->updatePassword($userData->id, password_hash($newPassword, PASSWORD_DEFAULT));
    }

    public function validateNewPassword(string $password, string $passwordConfirm): bool
    {
        if (empty($password) || empty($passwordConfirm)) {
            return false;
        }

        if ($password !== $passwordConfirm) {
            return false;
        }

        if (mb_strlen($password) < 6) {
            return false;
        }
        return true;
    }
}
