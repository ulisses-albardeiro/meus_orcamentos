<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Core\Helpers;
use App\Core\Message;
use App\Core\Session;
use App\Services\User\UserInterface;

class Admin implements IMiddleware
{
    public function __construct(private UserInterface $userService) {}

    public function handle(Request $request): void
    {
        $user = $this->userService->findUserById((new Session)->userId);

        if ($user->nivel != 1) {
            $mesagem = (new Message());
            $mesagem->mensagemErro('Acesso negado!')->flash();
            Helpers::redirect("login");
            exit;
        }
    }
}
