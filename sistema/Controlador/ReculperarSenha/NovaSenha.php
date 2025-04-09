<?php

namespace sistema\Controlador\ReculperarSenha;

use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;

class NovaSenha extends Controlador
{
    public function __construct()
    {
        parent::__construct('templates/views');
    }

    public function novaSenha(): void
    {
        $token = $this->validarToken();
        echo $this->template->rendenizar('reculperacao-senha/nova-senha.html', ['token' => $token]);
    }

    private function validarToken() : ?string
    {
        $token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

        if ($token) {
            $this->verificarTokenExiste($token);
            $this->verificarValidadeToken($token);
            return $token;
        }else{
            return null;
            Helpers::redirecionar('login');
        }
    }

    private function verificarTokenExiste(string $token): void 
    {
        $usuario = (new UsuarioModelo)->busca("token = '{$token}'")->resultado();
        if ($usuario == null) {
            $this->mensagem->mensagemSucesso("Solicitação inválida")->flash();
            Helpers::redirecionar('login');
        }
    }

    private function verificarValidadeToken($token) : void
    {
        $usuario = (new UsuarioModelo)->busca("token = '{$token}'")->resultado();

        $agora = strtotime(date('Y-m-d H:i:s'));
        $dt_hr_token = strtotime($usuario->dt_hr_token);
        $diferenca = $agora - $dt_hr_token;
        $tempo_em_horas = round($diferenca / 3600);
    
        if ($tempo_em_horas >= 2) {
            $this->mensagem->mensagemSucesso("Prazo da soliticitação expirado")->flash();
            Helpers::redirecionar('login');
        }
    }

    public function salvarNovaSenha(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $token = $dados['token'];
        //$this->validarSenha($senha['senha1'], $senha['senha2']);
        $usuario = (new UsuarioModelo);
        $dados_usuario = $usuario->busca("token = '{$token}'")->resultado();
        
        $usuario->id = $dados_usuario->id;
        $usuario->senha = password_hash($dados['senha1'], PASSWORD_DEFAULT);
        if ($usuario->salvar()) {
            $this->mensagem->mensagemSucesso("Senha alterada com sucesso")->flash();
            Helpers::redirecionar('login');
        }
    }

    function validarSenha($senha, $confirmar_senha): void
    {
        $sequencias = ["12345678", "abcdefgh", "87654321", "qwertyui"];
    
        if (strlen($senha) < 8) {
            $this->mensagem->mensagemErro("Solicitação inválida")->flash();
            Helpers::redirecionar('login');
        }
    
        if ($senha !== $confirmar_senha) {
            $this->mensagem->mensagemErro("Solicitação inválida")->flash();
            Helpers::redirecionar('login');
        }
    
        foreach ($sequencias as $seq) {
            if (strpos($senha, $seq) !== false) {
                $this->mensagem->mensagemErro("Solicitação inválida")->flash();
            Helpers::redirecionar('login');
            }
        }
    
        if (!preg_match('/[A-Z]/', $senha)) {
            $this->mensagem->mensagemErro("Solicitação inválida")->flash();
            Helpers::redirecionar('login');
        }
    
        if (!preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/°', $senha)) {
            $this->mensagem->mensagemErro("Solicitação inválida")->flash();
            Helpers::redirecionar('login');
        }
    }    
}
