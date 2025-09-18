<?php

namespace sistema\Controlador\Painel\Recibo;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ReciboModelo;
use sistema\Nucleo\Helpers;
use sistema\Suporte\Pdf;

class Recibo extends PainelControlador
{
    public function listar(): void
    {
        echo $this->template->rendenizar(
            "recibos/listar.html",
            [
                'recibos' => (new ReciboModelo)->getRecibos($this->usuario->id)
            ]
        );
    }

    public function gerar(?int $id_recibo = null): void
    {
        if (empty($id_recibo)) {
            $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
            $salvar = new ReciboModelo;
            $salvar->cadastrarRecibo($dados, $this->usuario->id);

        } else {
            $dados = (new ReciboModelo)->buscaPorId($id_recibo);
            $dados = json_decode($dados->recibo_completo, true);
        }

        $html = new Html;
        $html->dadosUsuario($dados['nome_empresa'], $dados['doc-empresa'], $dados['tel-empresa'], $dados['end-empresa'], $dados['email-empresa']);
        $html->img($this->usuario->img_logo);
        $html->dadosCliente($dados['nome-cliente'], $dados['doc-cliente'], $dados['valor-recibo']);
        $html->redesSociais($dados['facebook'], $dados['instagram']);
        $html->descricaoServico($dados['descricao']);

        $pdf = new Pdf;
        $pdf->carregarHTML($html->html());
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->baixar("recibo-" . Helpers::slug($dados['nome-cliente']) . ".pdf");
    }

    public function criar(): void
    {
        echo $this->template->rendenizar("recibos/form-recibos.html", []);
    }

    public function excluir(int $id_recibo): void
    {
        $excluir = new ReciboModelo;
        if ($excluir->excluirRecibo($id_recibo)) {
            $this->mensagem->mensagemSucesso("Recibo excluido com sucesso")->flash();
            Helpers::redirecionar("recibo/listar");
        } else {
            $this->mensagem->mensagemErro("Houve um erro inesperado")->flash();
            Helpers::redirecionar("recibo/listar");
        }
    }
}
