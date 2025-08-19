<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\Orcamento\Traits\CalculaOrcamento;
use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;
use sistema\Suporte\Pdf;

class OrcamentoControlador extends PainelControlador
{
    use CalculaOrcamento;

    public function listar(): void
    {
        $orcamentos = (new OrcamentoModelo)->buscaOrcamentos($this->usuario->id);

        echo $this->template->rendenizar(
            "orcamentos/listar.html",
            [
                'orcamentos' => $orcamentos,
                "titulo" => "Orçamentos"
            ]
        );
    }

    public function modelos(): void
    {
        echo $this->template->rendenizar(
            "orcamentos/modelos.html",
            [
                "titulo" => "Modelos"
            ]
        );
    }

    public function criar(string $modelo): void
    {
        echo $this->template->rendenizar(
            "orcamentos/forms/$modelo.html",
            [
                "titulo" => "Criar"
            ]
        );
    }

    public function exibir(string $modelo, int $id_orcamento): void
    {
        $dados_objeto = (new OrcamentoModelo)->buscaOrcamentosPorId($id_orcamento)[0];
        $dados_orcamento_json = json_decode($dados_objeto->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados_objeto, $dados_orcamento_json);

        $dados_usuario = $this->separarDadosUsuario($dados_completos);
        $dados_cliente = $this->separarDadosCliente($dados_completos);

        echo $this->template->rendenizar(
            "orcamentos/templates/$modelo.html",
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $dados_completos['itens'],
                'total_orcamento' => $dados_completos['vl_total'],
                'titulo' => $dados_usuario['nome-empresa'],
                'id_orcamento' => $id_orcamento,
            ]
        );
    }

    public function cadastrar(string $modelo): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $total_orcamento = $this->calcularTotalOrcamento($dados['itens']);

        $id_orcamento = (new OrcamentoModelo)->cadastrarOrcamento($dados['cliente_nome'], $total_orcamento, $dados, $this->usuario->id, $modelo);

        if (!empty($id_orcamento)) {
            Helpers::redirecionar("orcamento/$modelo/$id_orcamento");
        }
    }

    public function pdf(string $modelo, int $id_orcamento) : void
    {
        $dados_objeto = (new OrcamentoModelo)->buscaOrcamentosPorId($id_orcamento)[0];
        $dados_orcamento_json = json_decode($dados_objeto->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados_objeto, $dados_orcamento_json);

        $dados_usuario = $this->separarDadosUsuario($dados_completos);
        $dados_cliente = $this->separarDadosCliente($dados_completos);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $dados_completos['itens'],
                'total_orcamento' => $dados_completos['vl_total'],
                'titulo' => $dados_usuario['nome-empresa'],
            ]
        );

        $pdf = new Pdf;
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->exibir("Orçamento-" . Helpers::slug($dados_cliente['cliente_nome']) . ".pdf");
    }
}
