<?php
namespace sistema\Servicos\Login;

interface AuthInterface
{
    public function attempt(string $email, string $password): bool;
    public function check(): bool;
    public function logout(): void;
}