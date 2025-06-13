<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\Orcamento\Suporte\Suporte;
use sistema\Suporte\Template;

class OrcamentoControlador extends Suporte
{
    public function slim( string $id_orcamento): void
    {


        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $dados_usuario = $this->separarDadosUsuario($dados);
        $dados_cliente = $this->separarDadosCliente($dados);
        $total_orcamento = $this->calcularTotalOrcamento($dados['itens']);

        $template = (new Template('templates/pdf-templates/'));
        echo $template->rendenizar(
            'slim.html',
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $dados['itens'],
                'total_orcamento' => $total_orcamento,
            ]
        );
    }
}
