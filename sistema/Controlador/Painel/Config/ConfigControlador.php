<?php

namespace sistema\Controlador\Painel\Config;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\GerenciadorExclusaoUsuario;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Nucleo\Sessao;

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
        $exclusaoUsuario = new UsuarioModelo;


        if ($exclusaoDados->apagarRegistrosPorUsuarioGeral($idUsuario) && $exclusaoUsuario->apagarUsuario($idUsuario)) {
            $this->mensagem->mensagemSucesso("Conta apagada com sucesso!")->flash();
            Helpers::redirecionar('form-cadastro');
        } else {
            $this->mensagem->mensagemErro("Erro ao apagar a conta. Tente novamente.")->flash();
            Helpers::redirecionar('config');
        }
    }
}
