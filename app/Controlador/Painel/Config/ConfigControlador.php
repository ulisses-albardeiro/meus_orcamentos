<?php

namespace App\Controlador\Painel\Config;

use App\Controlador\Painel\PainelControlador;
use App\Modelos\GerenciadorExclusaoUsuario;
use App\Modelos\UserModel;
use App\Nucleo\Helpers;
use App\Nucleo\Sessao;

class ConfigControlador extends PainelControlador
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
