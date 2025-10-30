<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\EmpresasModelo;
use App\Core\Helpers;
use App\Core\Mensagem;
use App\Core\Sessao;

class Company implements IMiddleware
{
    protected Mensagem $mensagem;
    protected EmpresasModelo $empresaModelo;
    protected Sessao $sessao;

    public function handle(Request $request): void
    {
        $this->mensagem = new Mensagem();
        $this->sessao = new Sessao();
        $this->empresaModelo = new EmpresasModelo();
        $empresa = $this->empresaModelo->buscaEmpresaPorIdUsuario($this->sessao->userId);
        if ($empresa === null) {
            Helpers::redirecionar('empresa/cadastrar');
        }
    }
}
