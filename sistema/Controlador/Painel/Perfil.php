<?php
namespace sistema\Controlador\Painel;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Biblioteca\Upload;

class Perfil extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar("perfil.html", []);
    }

    public function editar() : void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($_FILES['imagem'])) {
            $upload = new Upload('templates/assets/img/');
            $upload->arquivo($_FILES['imagem'], Helpers::slug($dados['nome']), 'logos');
            if ($upload->getResultado()) {
                unlink('templates/site/assets/img/logos/' . $this->usuario->img_logo);
                $nome_arquivo = $upload->getResultado();
            }else {
                echo $upload->getErro();
                die;
            }
        }
        
        $usuario = (new UsuarioModelo);
        $usuario->id = $this->usuario->id;
        $usuario->img_logo = $nome_arquivo ?? $this->usuario->img_logo;
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