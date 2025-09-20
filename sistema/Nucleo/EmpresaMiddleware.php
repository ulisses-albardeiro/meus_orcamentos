<?php

namespace sistema\Nucleo;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Modelos\EmpresasModelo;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Mensagem;

class EmpresaMiddleware implements IMiddleware
{
    protected Mensagem $mensagem;
    protected EmpresasModelo $empresaModelo;
    protected Sessao $sessao;

    public function handle(Request $request): void
    {
        $this->mensagem = new Mensagem();
        $this->sessao = new Sessao();
        $this->empresaModelo = new EmpresasModelo();
        $empresa = $this->empresaModelo->buscaEmpresaPorIdUsuario($this->sessao->usuarioId);
        if ($empresa === null) {
            Helpers::redirecionar('empresa/cadastrar');
        }
    }
}
