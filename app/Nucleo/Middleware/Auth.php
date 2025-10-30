<?php

namespace App\Nucleo\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Nucleo\Helpers;
use App\Nucleo\Mensagem;
use App\Nucleo\Sessao;

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
