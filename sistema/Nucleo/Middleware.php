<?php

namespace sistema\Nucleo;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Controlador\UsuarioControlador;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;

class Middleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        $usuario = UsuarioControlador::usuario();
        if ($usuario->nivel != 0) {
            $mensagem = (new Mensagem());
            $mensagem->mensagemErro('Acesso negado.')->flash();
            Helpers::redirecionar("login");
            exit;
        }
    }
}
