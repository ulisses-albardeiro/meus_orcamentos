<?php

namespace sistema\Nucleo;

class Mensagem
{
    private $texto;
    private $css;

    public function __toString()
    {
        return $this->rendenizar();
    }

    public function mensagemSucesso(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-success';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    public function mensagemErro(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-danger';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    public function mensagemAtencao(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-warning';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }


    public function rendenizar(): string
    {
        return "<div class='{$this->css} alert alert-dismissible fade show d-flex justify-content-between align-items-center' role='alert'>
                <span>{$this->texto}</span>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar'></button>
            </div>";
    }


    private function filtrar(string $mensagem): string
    {
        return filter_var($mensagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function flash(): void
    {
        (new Sessao())->criarSessao('flash', $this);
    }
}
