<?php
namespace sistema\Controlador\Painel;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;

class Perfil extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar("perfil.html", []);
    }

    public function editar() : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        $usuario = (new UsuarioModelo);
        $usuario->id = $this->usuario->id;
        $usuario->cnpj = $dados['cnpj'];
        $usuario->nome = $dados['nome'];
        $usuario->email = $dados['email'];
        $usuario->facebook = $dados['face'];
        $usuario->instagram = $dados['insta'];
        $usuario->telefone = $dados['telefone'];
        $usuario->endereco = $dados['endereco'];
        if ($usuario->salvar()) {
            $this->mensagem->mensagemSucesso('Perfil atualizado com sucesso!')->flash();
            Helpers::redirecionar('perfil');
        }else{
            $this->mensagem->mensagemErro('Houve um erro inesperado')->flash();
            Helpers::redirecionar('perfil');
        }
    }
}