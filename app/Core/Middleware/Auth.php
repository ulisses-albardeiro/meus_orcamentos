<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Core\Helpers;
use App\Core\Mensagem;
use App\Core\Sessao;

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
