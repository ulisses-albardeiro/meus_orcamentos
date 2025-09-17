<?php

namespace sistema\Controlador\Painel\Perfil;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Biblioteca\Upload;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Servicos\Usuarios\UsuariosInterface;

class PerfilControlador extends PainelControlador
{
    protected UsuariosInterface $usuarioServico;

    public function __construct(UsuariosInterface $usuarioServico)
    {
        parent::__construct();
        $this->usuarioServico = $usuarioServico;
    }

    public function editar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $dadosUsuario = $this->usuarioServico->buscaUsuariosPorIdServico($this->usuario->usuarioId);
        if (isset($_FILES['imagem']) && $_FILES['imagem']['name'] != '') {
            $upload = new Upload('templates/assets/img/');
            $upload->arquivo($_FILES['imagem'], Helpers::slug($dados['nome']), 'perfil');
            if ($upload->getResultado()) {
                unlink($_SERVER['DOCUMENT_ROOT'].URL.'templates/assets/img/perfil/' . $dadosUsuario->img_logo);
                $nome_arquivo = $upload->getResultado();
            }
        }

        $usuario = (new UsuarioModelo);
        if (isset($nome_arquivo)) {
            $usuario->img_logo = $nome_arquivo;
        }
        $usuario->id = $this->usuario->usuarioId;
        $usuario->cnpj = $dados['cnpj'];
        $usuario->nome = $dados['nome'];
        $usuario->email = $dados['email'];
        $usuario->facebook = $dados['face'];
        $usuario->instagram = $dados['insta'];
        $usuario->telefone = $dados['telefone'];
        $usuario->endereco = $dados['endereco'];
        if ($usuario->salvar()) {
            $this->mensagem->mensagemSucesso('Perfil atualizado com sucesso!')->flash();
        }
        Helpers::redirecionar('config');
    }

    public function removerLogo(): void
    {
        $dadosUsuario = $this->usuarioServico->buscaUsuariosPorIdServico($this->usuario->usuarioId);

        unlink($_SERVER['DOCUMENT_ROOT'].URL.'templates/assets/img/perfil/' . $dadosUsuario->img_logo);
        $usuario = (new UsuarioModelo);
        $usuario->id = $this->usuario->usuarioId;
        $usuario->img_logo = null;
        if ($usuario->salvar()) {
            $this->mensagem->mensagemSucesso('Logo removida com sucesso!')->flash();
            Helpers::redirecionar('config');
        } else {
            $this->mensagem->mensagemErro('Houve um erro inesperado')->flash();
            Helpers::redirecionar('config');
        }
    }
}
