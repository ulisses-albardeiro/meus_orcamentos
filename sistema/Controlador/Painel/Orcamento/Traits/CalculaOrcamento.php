<?php

namespace sistema\Controlador\Painel\Orcamento\Traits;

trait CalculaOrcamento
{
    protected function calcularTotalOrcamento(array $dados): float
    {
        if (isset($dados['valor_orcamento'])) {
            return (float) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $dados['valor_orcamento']);
        }

        $total = 0;
        foreach ($dados['itens'] as $item) {
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
        $campos_desejados = [
            'cliente_nome',
            'cliente_documento',
            'cliente_telefone',
            'cliente_email',
            'cliente_endereco',
            'cliente_celular',
        ];

        $dados_cliente = [];

        foreach ($dados as $key => $valor) {
            if (in_array($key, $campos_desejados)) {
                $dados_cliente[$key] = $valor;
            }
        }

        return $dados_cliente;
    }

    protected function processarItensParaView(array $dados): array
    {
        $itens_processados = [];
        foreach ($dados['itens'] as $item) {

            //Se or tipo for simples o valor unitário dos itens será '0'.
            if (!isset($item['valor'])) {
                $item['valor'] = 0;
            }
            
            // Converte o valor para um formato numérico inteiro (centavos)
            $valorLimpo = (int) round($this->converterValorParaFloat($item['valor']) * 100);

            // Mantém os dados originais mas adiciona o valor processado
            $item_processado = $item;
            $item_processado['valor_limpo'] = $valorLimpo;
            $item_processado['valor_float'] = $valorLimpo / 100;

            $itens_processados[] = $item_processado;
        }

        return $itens_processados;
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
