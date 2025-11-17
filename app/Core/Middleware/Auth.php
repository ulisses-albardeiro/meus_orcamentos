<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Core\Helpers;
use App\Core\Message;
use App\Core\Session;

class Auth implements IMiddleware
{
    protected Message $mesagem;

    public function handle(Request $request): void
    {
        $this->mesagem = new Message();

        if (!(new Session)->check('userId')) {
            $this->mesagem->mensagemErro('Necessário fazer login!')->flash();
            Helpers::redirect('login');
        }
    }
}
