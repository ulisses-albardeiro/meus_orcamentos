<?php

namespace sistema\Nucleo\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Modelos\UserModel;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;
use sistema\Nucleo\Sessao;

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
