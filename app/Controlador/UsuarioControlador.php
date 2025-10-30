<?php

namespace App\Controlador;

use App\Modelos\EmpresasModelo;
use App\Modelos\UserModel;
use App\Nucleo\Controlador;
use App\Nucleo\Modelo;
use App\Nucleo\Sessao;

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