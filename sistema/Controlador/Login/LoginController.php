<?php

namespace sistema\Controlador\Login;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Sessao;

class LoginController extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/views');
    }

    public function create(): void
    {
        $this->checarSessao();

        echo $this->template->rendenizar('login.html', []);
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            if (in_array("", $dados)) {
                $this->mensagem->mensagemAtencao("Preencha todos os campos!")->flash();
                Helpers::redirecionar('login');
            } else {
                $usuario = (new UsuarioModelo())->login($dados, 1);
                if ($usuario) {
                    Helpers::redirecionar('home');
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
            Helpers::redirecionar('home');
        }
    }

    public function destroy(): void
    {
        $sessao = new Sessao;
        $sessao->deletarSessao('usuarioId');

        Helpers::redirecionar('/');
    }
}
