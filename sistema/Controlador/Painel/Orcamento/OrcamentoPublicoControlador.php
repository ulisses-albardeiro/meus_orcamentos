<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Nucleo\Controlador;
use sistema\Nucleo\Helpers;
use sistema\Modelos\OrcamentoModelo;
use sistema\Suporte\Pdf;
use sistema\Controlador\Painel\Orcamento\Traits\CalculaOrcamento;

class OrcamentoPublicoControlador extends Controlador
{
    use CalculaOrcamento;
    
    public function __construct()
    {
        parent::__construct('templates/views/painel');
    }

    public function exibir(string $modelo, string $hash): void
    {
        $dados_objeto = (new OrcamentoModelo)->buscaOrcamentosPorHash($hash)[0];
        
        $dados_orcamento_json = json_decode($dados_objeto->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados_objeto, $dados_orcamento_json);

        $dados_usuario = $this->separarDadosUsuario($dados_completos);
        $dados_cliente = $this->separarDadosCliente($dados_completos);

        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->processarItensParaView($dados_completos);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $itens_processados,
                'total_orcamento' => $dados_completos['vl_total'],
                'titulo' => $dados_usuario['nome-empresa'],
                'dados_completos' => $dados_completos,
            ]
        );

        $caminho_local = $_SERVER['DOCUMENT_ROOT'] . '/meus_orcamentos/templates/assets/arquivos/orcamentos/';

        $pdf = new Pdf;
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->salvarPDF($caminho_local, $hash . '.pdf');

        $orcamento_url = Helpers::url('templates/assets/arquivos/orcamentos/' . $hash . '.pdf');

        echo $this->template->rendenizar(
            "orcamentos/pre-view.html",
            [
                "orcamento" => $orcamento_url,
                "id_orcamento" => $dados_objeto->id,
            ]
        );
    }
}
