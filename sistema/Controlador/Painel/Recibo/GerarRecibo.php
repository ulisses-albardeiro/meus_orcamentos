<?php
namespace sistema\Controlador\Painel\Recibo;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Suporte\Pdf;

class GerarRecibo extends PainelControlador
{
    public function gerar() : void
    {
        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        $html = new Html;
        $html->dadosUsuario($dados['nome-empresa'], $dados['doc-empresa'], $dados['tel-empresa'], $dados['end-empresa'], $dados['email-empresa']);
        $html->img($this->usuario->img_logo);
        $html->dadosCliente($dados['nome-cliente'], $dados['doc-cliente'], $dados['valor-recibo']);
        $html->redesSociais($dados['facebook'], $dados['instagram']);
        $html->descricaoServico($dados['descricao']);

        $this->pdf($html->html(), $dados['nome-cliente']);
    }

    private function pdf(string $html, string $nome_cliente): void
    {
        $pdf = new Pdf;
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->baixar("recibo-" . Helpers::slug($nome_cliente) . ".pdf");
    }

    public function formRecibo() : void
    {
        echo $this->template->rendenizar("recibos/form-recibos.html", []);     
    }
}