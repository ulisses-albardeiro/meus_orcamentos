<?php

namespace App\Controllers\Panel\Config;

use App\Controllers\Panel\PanelController;
use App\Models\GerenciadorExclusaoUsuario;
use App\Models\UserModel;
use App\Core\Helpers;
use App\Core\Sessao;

class ConfigControlador extends PanelController
{
    public function listar(): void
    {
        
        echo $this->template->rendenizar(
            'config.html',
            [
                'titulo' => 'Configurações',
                'subTitulo' => 'Suas configurações da conta',
            ]
        );
    }

    public function excluir(int $idUsuario): void
    {
        $exclusaoDados = new GerenciadorExclusaoUsuario;
        $exclusaoUsuario = new UserModel;


        if ($exclusaoDados->apagarRegistrosPorUsuarioGeral($idUsuario) && $exclusaoUsuario->apagarUsuario($idUsuario)) {
            $sessao = new Sessao();
            $sessao->deletarSessao();
            Helpers::redirecionar('/');
        } else {
            $this->mensagem->mensagemErro("Erro ao apagar a conta. Tente novamente.")->flash();
            Helpers::redirecionar('config');
        }
    }
}
