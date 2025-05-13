<?php

namespace sistema\Controlador\Painel\Recibo;

use sistema\Nucleo\Helpers;

class Html
{
    protected ?string $img_logo = null;
    protected string $nome;
    protected string $documento;
    protected string $telefone;
    protected string $endereco;
    protected string $email;
    protected string $nome_cliente;
    protected string $documento_cliente;
    protected string $valor_recibo;

    public function html(): string
    {
        $html = '
        <!DOCTYPE html>
        <html lang="pt-BR">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recibo</title>
            <link rel="stylesheet" href="' . Helpers::url('templates/assets/css/recibo.css') . '">
        </head>

        <body>
            <div class="recibo-container">
                <!-- Cabeçalho com logo e dados do prestador -->
                <div class="cabecalho">'
            . $this->img_logo .
            '<div class="dados-prestador">
                        <h2>' . $this->nome . '</h2>
                        <div class="info-contato">' . $this->documento . '</div>
                        <div class="info-contato">' . $this->telefone . '</div>
                        <div class="info-contato">' . $this->endereco . '</div>
                        <div class="info-contato">' . $this->email . '</div>
                    </div>
                </div>

                <div class="corpo-recibo">
                    <!-- Título -->
                    <div class="recibo-header">
                        <h1>Recibo</h1>
                    </div>

                    <div class="texto-recibo">
                        <!-- Informações principais -->
                        <div class="recibo-info">
                            <p>Eu, <strong>' . $this->nome . '</strong>, portador do CPF <strong>' . $this->documento . '</strong>,</p>
                            <p>residente à <strong>' . $this->endereco . '</strong>,</p>
                            <p>DECLARO ter recebido de <strong>' . $this->nome_cliente . '</strong>, portador do CPF/CNPJ <strong>' . $this->documento_cliente . '</strong>,</p>
                            <p>a importância de:</p>
                        </div>

                        <!-- Valor numérico e por extenso -->
                        <div class="recibo-valor">
                            Valor: ' . $this->valor_recibo . '
                        </div>

                    </div>
                </div>

                <!-- Rodapé -->
                <div class="recibo-footer">
                    <p>Este documento serve como comprovante de pagamento e quitação da obrigação descrita.</p>
                    <p>Para dúvidas, entre em contato.</p>
                </div>
            </div>
        </body>

        </html>';

        return $html;
    }

    public function dadosUsuario(string $nome, ?string $documento = null, ?string $telefone = null, ?string $endereco = null, ?string $email = null): void
    {
        $this->nome = $nome;
        $this->documento = $documento;
        $this->telefone = $telefone;
        $this->endereco = $endereco;
        $this->email = $email;
    }

    public function img(?string $caminho = null): void
    {
        if ($caminho != null) {
            $img_logo = '
                <div class="logo-container">
                    <img src="' . $caminho . '" alt="Logo da empresa">
                </div>';

            $this->img_logo = $img_logo;
        }
    }

    public function dadosCliente(string $nome_cliente, string $documento_cliente, string $valor_recibo): void
    {
        $this->nome_cliente = $nome_cliente;
        $this->documento_cliente = $documento_cliente;
        $this->valor_recibo = $valor_recibo;
    }
}
