<?php

namespace sistema\Controlador\Painel\Lista\Suporte;

class Suporte
{
    protected function calcularTotalOrcamento(array $itens): float
    {
        $total = 0;

        foreach ($itens as $item) {
            $valor = (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $item['valor']);
            $valor = str_replace(',', '.', $valor);
            $total += (float)$valor * (int)$item['qtd'];
        }

        return $total / 100;
    }

    protected function separarDadosUsuario(array $dados): array
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

    protected function separarDadosCliente(array $dados): array
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
