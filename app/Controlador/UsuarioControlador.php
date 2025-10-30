<?php

namespace app\Controlador;

use app\Modelos\EmpresasModelo;
use app\Modelos\UserModel;
use app\Nucleo\Controlador;
use app\Nucleo\Modelo;
use app\Nucleo\Sessao;

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