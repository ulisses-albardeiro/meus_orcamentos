<?php

namespace sistema\Controlador\ReculperarSenha;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Controlador;
use sistema\Suporte\Email;
use sistema\Nucleo\Helpers;

class EmaiRecuperacao extends Controlador
{
    private string $token;
    private string $url;

    public function __construct()
    {
        parent::__construct('templates/views');
        $this->token = hash('sha256', random_bytes(64));
        $this->url = Helpers::url('nova-senha');
    }

    public function emaiRecuperacao(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);

        if ($email) {
            $dados_usuario = $this->verificacaoEmail($email);
            $this->salvarToken($dados_usuario->id, $this->token);
            $this->enviarEmail($email, $dados_usuario->nome);
            $this->mensagem->mensagemSucesso("Um link de reculperação de senha foi enviado para o seu email")->flash();
            Helpers::redirecionar('login');
        }

        echo $this->template->rendenizar('reculperacao-senha/email-de-recuperacao.html', ['email' => $email]);
    }

    private function corpoEmail(): string
    {
        $url = $this->url . '?token=' . $this->token;
        return <<<HTML
                   <body style="font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; padding: 20px;">
                       <div style="max-width: 500px; background: #ffffff; padding: 20px; margin: auto; border-radius: 5px;">
                           <h2 style="color: #333;">Recuperação de Senha</h2>
                           <p>Olá,</p>
                           <p>Clique no botão abaixo para redefinir sua senha.</p>
                           <a href="{$url}" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 15px; text-decoration: none; border-radius: 3px;">
                               Redefinir Senha
                           </a>
                           <p style="font-size: 12px; color: #777; margin-top: 20px;">Se você não solicitou, ignore este e-mail.</p>
                       </div>
                   </body>
                HTML;
    }


    private function verificacaoEmail(string $email): ?object
    {
        $verificacao = (new UsuarioModelo)->busca("email = '{$email}'")->resultado();

        if ($verificacao != null) {
            return $verificacao;
        }
        return null;
        $this->mensagem->mensagemSucesso("Email desconhecido")->flash();
        Helpers::redirecionar('recuperacao-de-senha');
    }

    private function salvarToken(int $id, string $token): void
    {
        $usuario = (new UsuarioModelo);
        $usuario->id = $id;
        $usuario->token = $token;
        $usuario->dt_hr_token = date('Y-m-d H:i:s');
        $usuario->salvar();
    }

    private function enviarEmail(string $email, string $nome): void
    {
        $enviar = (new Email(HOST_EMAIL, USUARIO_EMAIL, SENHA_EMAIL, PORTA_EMAIL));
        $enviar->criar('Reculperação de senha', $this->corpoEmail(), $email, $nome);
        $enviar->enviar(USUARIO_EMAIL, 'Reculperação de senha - Meus Orçamentos (Não responda)');
    }
}
