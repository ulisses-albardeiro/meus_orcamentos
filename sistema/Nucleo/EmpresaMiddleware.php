<?php

namespace sistema\Nucleo;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;

class EmpresaMiddleware implements IMiddleware
{
    protected Mensagem $mensagem;

    public function handle(Request $request): void
    {
        $this->mensagem = new Mensagem();

        if (!(new Sessao)->checarSessao('usuarioId')) {
            $this->mensagem->mensagemErro('Necessário fazer login!')->flash();
            Helpers::redirecionar('cadastro-empresa');
        }
    }
}
