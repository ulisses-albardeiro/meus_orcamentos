<?php

namespace App\Servicos\PasswordRecovery;

interface PasswordRecoveryInterface
{
    public function initiateRecovery(string $email): bool;
    public function validateToken(?string $token = null): ?string;
    public function saveNewPassword(string $token, string $newPassword): bool;
    public function validateNewPassword(string $password, string $passwordConfirm): bool;
}