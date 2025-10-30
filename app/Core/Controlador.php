<?php

namespace App\Core;

use App\Adapters\Template;

class Controlador{

    protected Template $template;
    protected Mensagem $mensagem;

    public function __construct(string $diretorio)
    {
        $this->template = new Template($diretorio);
        $this->mensagem = new Mensagem();
    }
}