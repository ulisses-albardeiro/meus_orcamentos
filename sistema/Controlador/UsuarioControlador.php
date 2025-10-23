<?php

namespace sistema\Controlador;

use sistema\Modelos\EmpresasModelo;
use sistema\Modelos\UserModel;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Modelo;
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
        if (!$sessao->checarSessao('userId')) {
            return null;
        }

        return (new UserModel)->buscaPorId($sessao->userId);
    }

    public static function empresa(): ?Modelo
    {
        $sessao = new Sessao();
        return (new EmpresasModelo)->buscaEmpresaPorIdUsuario($sessao->userId);
    }
}