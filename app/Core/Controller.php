<?php

namespace App\Core;

use App\Adapters\Template;

class Controller
{

    protected Template $template;
    protected Message $mensagem;

    public function __construct(string $directory)
    {
        $this->template = new Template($directory);
        $this->mensagem = new Message();
    }
}