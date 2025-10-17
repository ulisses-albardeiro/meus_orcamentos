<?php
namespace sistema\Controlador\Painel;

use sistema\Nucleo\Controlador;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Biblioteca\Upload;
use sistema\Servicos\Usuarios\UsuariosInterface;

class UserController extends Controlador
{
    protected UsuariosInterface $usuarioServico;

    public function __construct(UsuariosInterface $usuarioServico)
    {
        parent::__construct('templates/views');
        $this->usuarioServico = $usuarioServico;
    }

    function create() : void
    {
        echo $this->template->rendenizar("register.html", []);
    }

    public function store() : void
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

    public function update(int $id): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $dadosUsuario = $this->usuarioServico->buscaUsuariosPorIdServico($id);
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
        $usuario->id = $id;
        $usuario->nome = $dados['nome'];
        $usuario->email = $dados['email'];
        $usuario->telefone = $dados['telefone'];
    
        if ($usuario->salvar()) {
            $this->mensagem->mensagemSucesso('Perfil atualizado com sucesso!')->flash();
        }
        Helpers::redirecionar('config');
    }

    public function destroyImage(int $id): void
    {
        $dadosUsuario = $this->usuarioServico->buscaUsuariosPorIdServico($id);

        unlink($_SERVER['DOCUMENT_ROOT'].URL.'templates/assets/img/perfil/' . $dadosUsuario->img_logo);
        $usuario = (new UsuarioModelo);
        $usuario->id = $id;
        $usuario->img_logo = null;
        if ($usuario->salvar()) {
            $this->mensagem->mensagemSucesso('Foto removida com sucesso!')->flash();
            Helpers::redirecionar('config');
        } else {
            $this->mensagem->mensagemErro('Houve um erro inesperado')->flash();
            Helpers::redirecionar('config');
        }
    }
}
