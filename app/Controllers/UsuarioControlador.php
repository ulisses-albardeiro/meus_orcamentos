<?php

namespace App\Controllers;

use App\Models\EmpresasModelo;
use App\Models\UserModel;
use App\Core\Controller;
use App\Core\Model;
use App\Core\Sessao;

class UsuarioControlador extends Controller 
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

    public static function empresa(): ?Model
    {
        $sessao = new Sessao();
        return (new EmpresasModelo)->buscaEmpresaPorIdUsuario($sessao->userId);
    }
}