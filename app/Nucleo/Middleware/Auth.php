<?php

namespace app\Nucleo\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use app\Nucleo\Helpers;
use app\Nucleo\Mensagem;
use app\Nucleo\Sessao;

class Auth implements IMiddleware
{
    protected Mensagem $mesagem;

    public function handle(Request $request): void
    {
        $this->mesagem = new Mensagem();

        if (!(new Sessao)->checarSessao('userId')) {
            $this->mesagem->mensagemErro('Necessário fazer login!')->flash();
            Helpers::redirecionar('login');
        }
    }
}
