<?php
namespace sistema\Controlador\Painel;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;

class CadastroUsuario extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/views');
    }

    function form() : void
    {
        echo $this->template->rendenizar("form-cadastro.html", []);
    }

    public function cadastrar() : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->verificarExistenciaUsuario($dados['email']);

        $novo_usuario = (new UsuarioModelo);
        $novo_usuario->nome = $dados['nome'];
        $novo_usuario->email = $dados['email'];
        $novo_usuario->cadastrado_em = date('Y-m-d H:i:s');
        $novo_usuario->senha = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $novo_usuario->profissao = $dados['profissao'];

        if ($novo_usuario->salvar()) {
            $this->mensagem->mensagemSucesso("Cadastro feito com sucesso!")->flash();
            Helpers::redirecionar('login');
        }
    }

    public function verificarExistenciaUsuario(string $email) : void
    {
        $busca = (new UsuarioModelo)->busca("email = :e", ":e={$email}")->resultado();

        if (!empty($busca)) {
            $this->mensagem->mensagemAtencao("O email $email já possui uma conta, caso não saiba a senha, clique abaixo em 'Esqueceu sua senha' para fazer uma nova.")->flash();
            Helpers::redirecionar('login');
        }
    }
}