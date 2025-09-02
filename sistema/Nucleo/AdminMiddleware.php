<?php

namespace sistema\Nucleo;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;

class AdminMiddleware implements IMiddleware
{
    protected Mensagem $mensagem;

    public function handle(Request $request): void
    {
        $usuario = (new UsuarioModelo)->buscaPorId((new Sessao)->usuarioId);

        if ($usuario->nivel != 1) {
            $mensagem = (new Mensagem());
            $mensagem->mensagemErro('Acesso negado!')->flash();
            Helpers::redirecionar("login");
            exit;
        }
    }
}
