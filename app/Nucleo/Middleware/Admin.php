<?php

namespace App\Nucleo\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Modelos\UserModel;
use App\Nucleo\Helpers;
use App\Nucleo\Mensagem;
use App\Nucleo\Sessao;

class Admin implements IMiddleware
{
    public function handle(Request $request): void
    {
        $user = (new UserModel)->buscaPorId((new Sessao)->usuarioId);

        if ($user->nivel != 1) {
            $mesagem = (new Mensagem());
            $mesagem->mensagemErro('Acesso negado!')->flash();
            Helpers::redirecionar("login");
            exit;
        }
    }
}
