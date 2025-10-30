<?php

namespace app\Nucleo;

class Mensagem
{
    private $texto;
    private $css;
    private $link;
    private $linkTexto;
    private $modalId = 'mensagemModal';
    private $headerModal = 'Atenção';

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

    public function modal(string $headerModal, string $mensagem, string $link = '#', string $linkTexto = 'Continuar'): Mensagem
    {
        $this->css = 'modal';
        $this->headerModal = $this->filtrar($headerModal);
        $this->texto = $this->filtrar($mensagem);
        $this->link = $link;
        $this->linkTexto = $this->filtrar($linkTexto);
        return $this;
    }

    public function rendenizar(): string
    {
        if ($this->css === 'modal') {
            return $this->renderModal();
        }

        return "<div class='{$this->css} alert-dismissible fade show d-flex justify-content-between align-items-center' role='alert'>
                    <span>{$this->texto}</span>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar'></button>
                </div>";
    }

    private function renderModal(): string
    {
        return "
    <div class='modal fade' id='{$this->modalId}' tabindex='-1' aria-labelledby='{$this->modalId}Label' aria-hidden='true'>
        <div class='modal-dialog modal-dialog-centered'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='{$this->modalId}Label'>{$this->headerModal}</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Fechar'></button>
                </div>
                <div class='modal-body'>
                    {$this->texto}
                </div>
                <div class='modal-footer'>
                    <a href='{$this->link}' class='botao'>{$this->linkTexto}</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('{$this->modalId}');
            var modal = new bootstrap.Modal(modalElement);

            modalElement.addEventListener('shown.bs.modal', function () {
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            });

            modal.show();
        });
    </script>
    ";
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
