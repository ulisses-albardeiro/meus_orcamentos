<?php

namespace sistema\Servicos\Orcamentos;

use sistema\Modelos\OrcamentoModelo;

class OrcamentosServicos implements OrcamentosInterface
{
    protected OrcamentoModelo $orcamentoModelo;

    public function __construct(OrcamentoModelo $orcamentoModelo)
    {
        $this->orcamentoModelo = $orcamentoModelo;
    }

    public function calcularTotalOrcamento(array $dados): int
    {
        if (isset($dados['valor_orcamento'])) {
            $total =  (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $dados['valor_orcamento']);
            return $total;
        }

        $total = 0;
        foreach ($dados['itens'] as $item) {
            $valor = (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $item['valor']);
            $valor = str_replace(',', '.', $valor);
            $total += (int)$valor * (int)$item['qtd'];
        }

        return $total;
    }

    public function separarDadosUsuario(array $dados): array
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

    public function separarDadosCliente(array $dados): array
    {
        $campos_desejados = [
            'nome_cliente',
            'documento_cliente',
            'telefone_cliente',
            'email_cliente',
            'endereco_cliente',
            'celular_cliente',
            'cep_cliente',
            'rua_cliente',
            'n_casa_cliente',
            'bairro_cliente',
            'cidade_cliente',
            'uf_cliente',
        ];

        $dados_cliente = [];

        foreach ($dados as $key => $valor) {
            if (in_array($key, $campos_desejados)) {
                $dados_cliente[$key] = $valor;
            }
        }

        return $dados_cliente;
    }

    public function processarItensParaView(array $dados): array
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

    public function converterValorParaFloat(string $valorFormatado): float
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

    public function buscaOrcamentosServico(int $id_usuario): ?array
    {
        return $this->orcamentoModelo->buscaOrcamentos($id_usuario);
    }

    public function buscaOrcamentoPorHashServico(string $hash): ?array
    {
        $dados_objeto = $this->orcamentoModelo->buscaOrcamentosPorHash($hash)[0];

        $dados_orcamento_json = json_decode($dados_objeto->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados_objeto, $dados_orcamento_json);
        return $dados_completos;
    }

    public function excluirOrcamentoServico(string $hash): bool
    {
        return $this->orcamentoModelo->excluirOrcamento($hash);
    }

    public function buscaOrcamentoPorIdServico(int $id_orcamento): ?array
    {
        $dados = $this->orcamentoModelo->buscaOrcamentosPorId($id_orcamento)[0];
        $dados_orcamento_json = json_decode($dados->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados, $dados_orcamento_json);

        return $dados_completos;
    }
}
