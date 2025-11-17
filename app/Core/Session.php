<?php

namespace App\Core;

class Session
{
    public function __construct()
    {
        if(!session_id()){
            session_start();
        }
    }

    public function create(string $chave, mixed $valor) : Session
    {
        $_SESSION[$chave] = (is_array($valor) ? (object) $valor : $valor);

        return $this;
    }

    public function clear(string $chave) : Session
    {
        unset($_SESSION[$chave]);

        return $this;
    }

    public function loading() : ? object
    {
        return (object) $_SESSION;
    }

    public function check(string $chave) : bool
    {
        return isset($_SESSION[$chave]);
    }

    public function destroy() : Session
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

    public function flash(): ?Message
    {
        if($this->check('flash')){
            $flash = $this->flash;
            $this->clear('flash');
            return $flash;
        }

        return null;
    }
}