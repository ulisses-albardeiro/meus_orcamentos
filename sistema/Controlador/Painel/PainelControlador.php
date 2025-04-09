<?php

namespace sistema\Controlador\Painel;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Controlador\UsuarioControlador;
use sistema\Nucleo\Sessao;

class PainelControlador extends Controlador
{
    protected $usuario;

    public function __construct()
    {
        parent::__construct('templates/views/painel');

        $this->usuario = UsuarioControlador::usuario();

        if(!$this->usuario){
            $this->mensagem->mensagemErro('Necessário fazer login!')->flash();

            $sessao = new Sessao;
            $sessao->limparSessao('usuarioId');

            Helpers::redirecionar('login');
        }

    }

   
}