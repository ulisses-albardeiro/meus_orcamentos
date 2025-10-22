<?php

namespace sistema\Nucleo\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;
use sistema\Nucleo\Sessao;

class Auth implements IMiddleware
{
    protected Mensagem $mesagem;

    public function handle(Request $request): void
    {
        $this->mesagem = new Mensagem();

        if (!(new Sessao)->checarSessao('usuarioId')) {
            $this->mesagem->mensagemErro('Necessário fazer login!')->flash();
            Helpers::redirecionar('login');
        }
    }
}
