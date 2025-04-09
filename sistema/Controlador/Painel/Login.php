<?php

namespace sistema\Controlador\Painel;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Sessao;

class Login extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/views');
    }

    public function login(): void
    {
        $this->checarSessao();

        echo $this->template->rendenizar('login.html', []);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            if (in_array("", $dados)) {
                $this->mensagem->mensagemAtencao("Preencha todos os campos!")->flash();
                Helpers::redirecionar('login');
            } else {
                $usuario = (new UsuarioModelo())->login($dados, 1);
                if ($usuario) {
                    Helpers::redirecionar('dashboard');
                }
            }
        }
    }

    public function checarDados(array $dados): bool
    {
        if (empty($dados['email']) or empty($dados['senha'])) {
            return false;
        }

        return true;
    }

    private function checarSessao(): void
    {
        if ((new Sessao)->checarSessao('usuarioId')) {
            Helpers::redirecionar('perfil');
        }
    }
}
