<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;
use sistema\Suporte\Pdf;
use DateTime;

class GerarOrcamento_1 extends PainelControlador
{
    public function gerar(?int $id_orcamento = null): void
    {
        if ($id_orcamento) {
            $modelo = new OrcamentoModelo();
            $orcamento_array = (array) $modelo->buscaPorId($id_orcamento);

            $obj_dados = $orcamento_array["\0*\0dados"] ?? null;

            if (!$obj_dados || !isset($obj_dados->orcamento_completo)) {
                echo "Orçamento não encontrado ou dados inválidos.";
                return;
            }
            $dados = json_decode($obj_dados->orcamento_completo, true);
        } else {
            $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        }

        $html = $this->html($dados);

        $total = 0;
        foreach ($dados['itens'] as $item) {

            $valor = (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $item['valor-item']);
            $valor = $valor / 100;
            $qtd_item = (int) $item['qtd-item'];
            $subtotal = $qtd_item * $valor;
            $total += $subtotal;
            $total = number_format($total, 2, ',', '.');
        }

        if ($id_orcamento == null) {
            $cadastrar = (new OrcamentoModelo);
            if (!$cadastrar->cadastrarOrcamento($dados['nome-cliente'], $total, $dados, $this->usuario->id, "detalhado")) {
                echo "falhou";
                die;
            }
        }


        $pdf = new Pdf();
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->baixar("Orçamento_" . trim($dados['nome-cliente']) . ".pdf");
    }

    private function html(array $dados): string
    {
        $url = Helpers::url('templates/assets/img/logos/' . $this->usuario->img_logo);
        $img_logo = '';
        if (!empty($this->usuario->img_logo)) {
            $img_logo = <<<HTML
                <div class="logo-container">
                    <img src="{$url}"class="logo">
                </div>
        HTML;
        }

        $css = Helpers::url('templates/assets/css/orcamento.css');
        $total = 0;
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
            $end_empresa = <<<HTML
            <p class="info-contato">
                <span class="icone"></span>{$dados['end-empresa']}
            </p>
        HTML;
        }

        $email_empresa = '';
        if (!empty($dados['email-empresa'])) {
            $email_empresa = <<<HTML
            <p class="info-contato">
               <span class="icone"></span>{$dados['email-empresa']}
            </p>
        HTML;
        }

        $doc_cliente = '';
        if (!empty($dados['doc-cliente'])) {
            $doc_cliente = <<<HTML
            <div style="padding: 2px 10px;">
                <p style="margin: 2px 0; line-height: 1.2;"><strong>CNPJ:</strong> {$doc_cliente}</p>       
            </div>
        HTML;
        }

        $end_cliente = '';
        if (!empty($dados['end-cliente'])) {
            $end_cliente = <<<HTML
            <div style="border-bottom: 1px solid #e9ecef; padding: 2px 10px;">
                <p style="margin: 2px 0; line-height: 1.2;"><strong>Endereço:</strong> {$dados['end-cliente']}</p>
            </div>
        HTML;
        }

        $tel_cliente = '';
        if (!empty($dados['tel-cliente'])) {
            $tel_cliente = "<strong>Telefone: </strong>".$dados['tel-cliente'];
        }

        $cel_cliente = '';
        if (!empty($dados['cel-cliente'])) {
            $cel_cliente ='<strong> Celular: </strong>'.$dados['cel-cliente']; 
        }

        $email_cliente = '';
        if (!empty($dados['email-cliente'])) {
            $email_cliente = "<strong> Email: </strong>".$dados['email-cliente'];
        }

        $contatos = '';
        if(!empty($dados['tel-cliente']) || !empty($dados['cel-cliente']) || !empty($dados['email-cliente'])){
            $contatos = <<<HTML
            <div style="border-bottom: 1px solid #e9ecef; padding: 2px 10px; display: flex;">
                <p style="margin: 2px 0; line-height: 1.2;"> {$tel_cliente}{$cel_cliente} 
                {$email_cliente}</p>      
            </div>
        HTML;
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

            $valor = (int) str_replace(['R$', '.', ',', "\xC2\xA0", ' '], ['', '', '', '', ''], $item['valor-item']);

            $valor = $valor / 100;
            $qtd_item = (int) $item['qtd-item'];

            $subtotal = $qtd_item * $valor;
            $total += $subtotal;
            $valorItemFormatado = number_format($valor, 2, ',', '.');
            $subtotalFormatado = number_format($subtotal, 2, ',', '.');

            $itensHTML .= <<<HTML
            <tr>
                <td class="td">{$item['qtd-item']}</td>
                <td class="td">{$item['nome-item']}<br><small style="font-size: 13px; font-style: italic">{$item['descricao-item']}</small></td>
                <td  class="text-right td">R$ {$valorItemFormatado}</td>
                <td  class="text-right td">R$ {$subtotalFormatado}</td>
            </tr>
    HTML;
        }
        if (!empty($dados['validade-orcamento'])) {
            $validade_orcamento = $dados['validade-orcamento'];
            $data = new DateTime($validade_orcamento);
            $data_formatada = $data->format('d/m/Y');
            $validade = isset($dados['validade-orcamento']) ? "*Validade do orçamento: " . $data_formatada : null;
        }

        $anotacoes = !empty($dados['anotacoes']) ? "*" . $dados['anotacoes'] : null;
        $totalFormatado = number_format($total, 2, ',', '.');

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="pt_BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
            <link rel="stylesheet" href="{$css}">
            <title>Orçamento - {$dados['nome-cliente']}</title>
        </head>
        <body>
            <div class="container">
                 <!-- Cabeçalho do PDF -->
                <div class="cabecalho-orcamento">
                    <div class="cabecalho-container">
                        <div class="logo-dados">
                            {$img_logo}
                            <div class="dados-prestador">
                                <h2 class="nome-prestador">{$dados['nome-empresa']}</h2>
                                <p class="info-contato">
                                    <span class="icone"></span>{$dados['tel-empresa']}
                                </p>
                                {$doc_empresa}
                                {$email_empresa}
                                {$end_empresa}
                                <p class="social-link">
                                    {$facebook}
                                    {$instagram}
                                </p>
                            </div>
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
                    {$end_cliente}
                    {$contatos}                    
                    {$doc_cliente}
                </div>
                <div class="section-title-orcamento">
                        Orçamento
                    </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Qtd.</th>
                            <th>Descrição</th>
                            <th class="text-right">Valor Unit.</th>
                            <th class="text-right">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$itensHTML}
                    </tbody>
                </table>
                
                <div class="total-section">
                    <div class="total-line">
                        <div class="total-label">Total:</div>
                        <div class="total-value grand-total">R$ {$totalFormatado}</div>
                    </div>
                </div>
                
                <div class="footer">
                    <p>{$validade}</p>
                    <p>{$anotacoes}</p>
                    <p>Obrigado por solicitar um orçamento conosco! Para dúvidas, entre em contato.</p>
                </div>
            </div>
        </body>
        </html>
        HTML;

        return $html;
    }
}
