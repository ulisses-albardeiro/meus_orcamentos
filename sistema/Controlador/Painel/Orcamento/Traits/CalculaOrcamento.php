<?php

namespace sistema\Controlador\Painel\Orcamento\Traits;

trait CalculaOrcamento
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

    protected function processarItensParaView(array $itens): array
    {
        $itensProcessados = [];

        foreach ($itens as $item) {
            // Converte o valor para um formato numérico inteiro (centavos)
            $valorLimpo = (int) round($this->converterValorParaFloat($item['valor']) * 100);

            // Mantém os dados originais mas adiciona o valor processado
            $itemProcessado = $item;
            $itemProcessado['valor_limpo'] = $valorLimpo;
            $itemProcessado['valor_float'] = $valorLimpo / 100;

            $itensProcessados[] = $itemProcessado;
        }

        return $itensProcessados;
    }

    protected function converterValorParaFloat(string $valorFormatado): float
    {
        // Remove "R$", espaços e caracteres não numéricos exceto vírgula e ponto
        $valorLimpo = preg_replace('/[^\d,\.]/', '', $valorFormatado);

        // Se tiver ponto como separador de milhar e vírgula como decimal
        if (preg_match('/^\d{1,3}(?:\.\d{3})*,\d{2}$/', $valorLimpo)) {
            $valorLimpo = str_replace('.', '', $valorLimpo);
            $valorLimpo = str_replace(',', '.', $valorLimpo);
        }
        // Se tiver vírgula como separador de milhar e ponto como decimal
        elseif (preg_match('/^\d{1,3}(?:,\d{3})*\.\d{2}$/', $valorLimpo)) {
            $valorLimpo = str_replace(',', '', $valorLimpo);
        }
        // Se tiver apenas vírgula como decimal
        elseif (preg_match('/^\d+,\d{2}$/', $valorLimpo)) {
            $valorLimpo = str_replace(',', '.', $valorLimpo);
        }

        return (float) $valorLimpo;
    }
}
