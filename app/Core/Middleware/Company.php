<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\CompanyModel;
use App\Core\Helpers;
use App\Core\Message;
use App\Core\Session;

class Company implements IMiddleware
{
    protected Message $mensagem;
    protected CompanyModel $empresaModelo;
    protected Session $sessao;

    public function handle(Request $request): void
    {
        $this->mensagem = new Message();
        $this->sessao = new Session();
        $this->empresaModelo = new CompanyModel();
        $empresa = $this->empresaModelo->findCompanyByUserId($this->sessao->userId);
        if ($empresa === null) {
            Helpers::redirect('company/create');
        }
    }
}
