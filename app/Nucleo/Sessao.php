<?php

namespace App\Nucleo;

class Sessao
{
    public function __construct()
    {
        if(!session_id()){
            session_start();
        }
    }

    public function criarSessao(string $chave, mixed $valor) : Sessao
    {
        $_SESSION[$chave] = (is_array($valor) ? (object) $valor : $valor);

        return $this;
    }

    public function limparSessao(string $chave) : Sessao
    {
        unset($_SESSION[$chave]);

        return $this;
    }

    public function carregarSessao() : ? object
    {
        return (object) $_SESSION;
    }

    public function checarSessao(string $chave) : bool
    {
        return isset($_SESSION[$chave]);
    }

    public function deletarSessao() : Sessao
    {
        session_destroy();
        return $this;
    }

    public function __get($chave)
    {
        if(!empty($_SESSION[$chave])){
            return $_SESSION[$chave];
        }
    }

    public function flash(): ? Mensagem
    {
        if($this->checarSessao('flash')){
            $flash = $this->flash;
            $this->limparSessao('flash');
            return $flash;
        }

        return null;
    }
}