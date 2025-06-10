<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Suporte\Template;

class OrcamentoSlim
{
    public function gerarPDF(): void
    {
        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);

     /*    echo "<pre>";
        var_dump($dados); */

        $dados_usuario = $this->separarDadosUsuario($dados);
        $dados_cliente = $this->separarDadosCliente($dados);

        $template = (new Template('templates/pdf-templates/'));
        echo $template->rendenizar(
            'slim.html',
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
            ]
        );
    }

    private function  somarValores(array $valores)
    {
        $total = 0;
        foreach ($valores as $key => $valor) {
            $total + $valor;
        }

        return $total;
    }

    private function separarDadosUsuario(array $dados): array
    {
        $campos_desejados = [
            'nome-empresa',
            'email-empresa',
            'tel-empresa',
            'doc-empresa',
            'facebook',
            'instagram',
            'end-empresa'
        ];

        $dados_usuario = [];

        foreach ($dados as $key => $valor) {
            if (in_array($key, $campos_desejados)) {
                $dados_usuario[$key] = $valor;
            }
        }

        return $dados_usuario;
    }

    private function separarDadosCliente(array $dados): array
    {
        $camposDesejados = [
            'cliente_nome',
            'cliente_documento',
            'cliente_telefone',
            'cliente_email'
        ];

        $dadosCliente = [];

        foreach ($dados as $key => $valor) {
            if (in_array($key, $camposDesejados)) {
                $dadosCliente[$key] = $valor;
            }
        }

        return $dadosCliente;
    }
}
