<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\UserModel;
use App\Core\Helpers;
use App\Core\Message;
use App\Core\Sessao;

class Admin implements IMiddleware
{
    public function handle(Request $request): void
    {
        $user = (new UserModel)->buscaPorId((new Sessao)->usuarioId);

        if ($user->nivel != 1) {
            $mesagem = (new Message());
            $mesagem->mensagemErro('Acesso negado!')->flash();
            Helpers::redirecionar("login");
            exit;
        }
    }
}
