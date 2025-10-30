<?php

namespace App\Nucleo;
use App\Adapter\Template;

class Controlador{

    protected Template $template;
    protected Mensagem $mensagem;

    public function __construct(string $diretorio)
    {
        $this->template = new Template($diretorio);
        $this->mensagem = new Mensagem();
    }
}