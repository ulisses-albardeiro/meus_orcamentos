<?php

namespace sistema\Nucleo;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Controlador\UsuarioControlador;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;

class AdminMiddleware implements IMiddleware
{
    protected $usuario;
    protected Mensagem $mensagem;
    protected Sessao $sessao;

    public function handle(Request $request): void
    {
        $this->sessao = (new Sessao)->usuarioId;

        if ($this->sessao->nivel != 1) {
            $mensagem = (new Mensagem());
            $mensagem->mensagemErro('Acesso negado.')->flash();
            Helpers::redirecionar("login");
            exit;
        }
    }
}
