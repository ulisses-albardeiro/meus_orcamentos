<?php

namespace App\Controllers;

use App\Models\EmpresasModelo;
use App\Models\UserModel;
use App\Core\Controlador;
use App\Core\Modelo;
use App\Core\Sessao;

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