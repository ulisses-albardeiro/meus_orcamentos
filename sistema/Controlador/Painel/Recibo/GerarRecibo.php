<?php
namespace sistema\Controlador\Painel\Recibo;

use sistema\Controlador\Painel\PainelControlador;
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

        $pdf = new Pdf;
        $pdf->carregarHTML($html->html());
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->baixar("recibo-" . trim($dados['nome-cliente']) . ".pdf");

    }

    public function formRecibo() : void
    {
        echo $this->template->rendenizar("recibos/form-recibos.html", []);     
    }
}