<?php

namespace sistema\Controlador;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Sessao;

class UsuarioControlador extends Controlador 
{
    public function __construct()
    {
        parent::__construct('templates/views');
    }      
    
    public static function usuario()
    {
        $sessao = new Sessao();
        if (!$sessao->checarSessao('usuarioId')) {
            return null;
        }

        return (new UsuarioModelo)->buscaPorId($sessao->usuarioId);
    }
    
}