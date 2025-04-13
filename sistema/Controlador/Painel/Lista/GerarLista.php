<?php

namespace sistema\Controlador\Painel\Lista;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\ListaModelo;
use sistema\Suporte\Pdf;
use sistema\Nucleo\Helpers;

class GerarLista extends PainelControlador
{
    public function criar(): void
    {
        echo $this->template->rendenizar("listas/criar-lista.html", []);
    }

    public function gerar(): void
    {
        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        $html = $this->html($dados);

        (new ListaModelo)->cadastrarLista($dados['nome-cliente'], $dados, $this->usuario->id);

        $pdf = new Pdf();
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->exibir("Lista_Materiais_" . $dados['nome-cliente'] . ".pdf");
    }

    private function html(array $dados): string
    {
        $url = Helpers::url('templates/assets/img/gerais/img.png');
        $css = Helpers::url('templates/assets/css/orcamento.css');
        $itensHTML = '';

        $doc_empresa = '';
        if (!empty($dados['doc-empresa'])) {
            $doc_empresa = <<<HTML
            <p class="info-contato">
                <span class="icone"></span>{$dados['doc-empresa']}
            </p>
        HTML;
        }

        $end_empresa = '';
        if (!empty($dados['end-empresa'])) {
            $doc_empresa = <<<HTML
            <p class="info-contato">
                <span class="icone"></span>{$dados['end-empresa']}
            </p>
        HTML;
        }

        $doc_cliente = $dados['doc-cliente'];
        if (empty($dados['doc-cliente'])) {
            $doc_cliente = 'Não se aplica';
        }

        $facebook = "";
        if ($dados['facebook']) {
            $facebook = '<img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook" class="social-icon"> ' . $dados['facebook'];
        }

        $instagram = "";
        if ($dados['instagram']) {
            $instagram = '<img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" class="social-icon"> ' . $dados['instagram'];
        }

        foreach ($dados['itens'] as $item) {
            $itensHTML .= <<<HTML
            <tr>
                <td style="max-width: 2px;" class="td">{$item['qtd-item']}</td>
                <td class="td">{$item['nome-item']}<br><small style="font-size: 13px; font-style: italic">{$item['descricao-item']}</small></td>
            </tr>
        HTML;
        }

        $anotacoes = !empty($dados['anotacoes']) ? "*" . $dados['anotacoes'] : null;

        $html = <<<HTML
<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{$css}">
    <title>Lista de Materiais - {$dados['nome-cliente']}</title>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho do PDF -->
        <div class="cabecalho-orcamento">
            <div class="cabecalho-container">
                <div class="logo-dados">
                    <div class="dados-prestador">
                        <h2 class="nome-prestador">{$dados['nome-empresa']}</h2>
                        <p class="info-contato">
                            <span class="icone"></span>{$dados['tel-empresa']}
                        </p>
                        {$doc_empresa}
                        <p class="info-contato">
                            <span class="icone"></span>{$dados['email-empresa']}
                        </p>
                        {$end_empresa}
                    </div>
                </div>
                
                <div class="redes-sociais">
                    <p class="social-link">
                        {$facebook}
                        {$instagram}
                     </p>
                </div>
            </div>
        </div>
        <!-- End Cabeçalho PDF -->
             
        <div style="background-color: var(--dark); margin-bottom: -20px">
            <h3 style="color: white; text-align: center; padding: 8px 0;">Dados do Cliente</h3>
        </div>
        <div style="border: 1px solid #e9ecef">
            <div style="border-bottom: 1px solid #e9ecef; padding: 2px 10px;">
                <p style="margin: 2px 0; line-height: 1.2;"><strong>Nome:</strong> {$dados['nome-cliente']}</p>
            </div>
            <div style="border-bottom: 1px solid #e9ecef; padding: 2px 10px;">
                <p style="margin: 2px 0; line-height: 1.2;"><strong>Endereço:</strong> {$dados['end-cliente']}</p>
            </div>
            <div style="border-bottom: 1px solid #e9ecef; padding: 2px 10px; display: flex;">
                <p style="margin: 2px 0; line-height: 1.2;"><strong>Telefone:</strong> {$dados['tel-cliente']} / {$dados['cel-cliente']}  |  
                <strong>Email:</strong> {$dados['email-cliente']}</p>      
            </div>
            <div style="padding: 2px 10px;">
                <p style="margin: 2px 0; line-height: 1.2;"><strong>CNPJ:</strong> {$doc_cliente}</p>       
            </div>
        </div>

        <div class="section-title-orcamento">
            Lista de Materiais
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th style="max-width: 5px">Quantidade</th>
                    <th>Material</th>
                </tr>
            </thead>
            <tbody>
                {$itensHTML}
            </tbody>
        </table>
        
        <div class="footer">
            <p>{$anotacoes}</p>
        </div>
    </div>
</body>
</html>
HTML;

        return $html;
    }
}
