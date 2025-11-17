<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\CompanyModel;
use App\Core\Helpers;
use App\Core\Message;
use App\Core\Sessao;

class Company implements IMiddleware
{
    protected Message $mensagem;
    protected CompanyModel $empresaModelo;
    protected Sessao $sessao;

    public function handle(Request $request): void
    {
        $this->mensagem = new Message();
        $this->sessao = new Sessao();
        $this->empresaModelo = new CompanyModel();
        $empresa = $this->empresaModelo->findCompanyByUserId($this->sessao->userId);
        if ($empresa === null) {
            Helpers::redirect('empresa/cadastrar');
        }
    }
}
