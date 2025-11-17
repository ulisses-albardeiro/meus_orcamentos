<?php

namespace App\Services\Login;

use App\Models\UserModel;
use App\Core\Helpers;
use App\Core\Session;
use App\Services\Login\AuthInterface;

class LoginService implements AuthInterface
{
    public function __construct(private UserModel $userModel, private Session $session) {}

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

        if ($user->status != 1) {
            return false;
        }
        
        $this->session->create('userId', $user->id);

        return true;
    }

    public function check(): bool
    {
        return $this->session->check('userId');
    }

    public function logout(): void
    {
        $this->session->destroy();
    }
}
