<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Modelos\UsuarioModelo;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clientes\ClientesInterface;
use sistema\Servicos\Orcamentos\OrcamentosInterface;
use sistema\Suporte\Pdf;

class OrcamentoControlador extends PainelControlador
{
    protected OrcamentosInterface $orcamentosServicos;
    protected ClientesInterface $clientesServico;

    public function __construct(OrcamentosInterface $orcamentosServicos, ClientesInterface $clientesServico)
    {
        parent::__construct();
        $this->orcamentosServicos = $orcamentosServicos;
        $this->clientesServico = $clientesServico;
    }

    public function listar(): void
    {
        $orcamentos = $this->orcamentosServicos->buscaOrcamentosServico($this->usuario->usuarioId);
        $clientes = $this->clientesServico->buscaClientesPorIdUsuarioServico($this->usuario->usuarioId);

        echo $this->template->rendenizar(
            "orcamentos/listar.html",
            [
                'orcamentos' => Helpers::colocarTodosNomesClientesPeloId($clientes, $orcamentos),
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

    public function criar(string $form, string $modelo): void
    {
        echo $this->template->rendenizar(
            "orcamentos/forms/$form.html",
            [
                "titulo" => "Criar Orçamento",
                "modelo" => $modelo,
                "clientes" => $this->clientesServico->buscaClientesPorIdUsuarioServico($this->usuario->usuarioId)?? [],
            ]
        );
    }

    public function cadastrar(string $modelo): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $id_cliente = $this->clientesServico->cadastraClientesServico($dados, $this->usuario->usuarioId);

        $total_orcamento = $this->orcamentosServicos->calcularTotalOrcamento($dados);
        $hash = Helpers::gerarHash();

        $id_orcamento = (new OrcamentoModelo)->cadastrarOrcamento($id_cliente, $total_orcamento, $dados, $this->usuario->usuarioId, $modelo, $hash);

        if (!empty($id_orcamento)) {
            //redireciona para o método 'exibir'
            Helpers::redirecionar("orcamento/$modelo/$hash");
        }
    }

    public function pdf(string $modelo, int $id_orcamento): void
    {
        $dados_objeto = (new OrcamentoModelo)->buscaOrcamentosPorId($id_orcamento)[0];
        $dados_orcamento_json = json_decode($dados_objeto->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados_objeto, $dados_orcamento_json);


        $dados_usuario = $this->orcamentosServicos->separarDadosUsuario($dados_completos);
        $dados_cliente = $this->orcamentosServicos->separarDadosCliente($dados_completos);

        /* echo "<pre>";
        var_dump($dados_completos);
        die; */


        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->orcamentosServicos->processarItensParaView($dados_completos);

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

        $pdf = new Pdf;
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->baixar("orçamento-" . Helpers::slug($dados_cliente['nome_cliente']) . ".pdf");
    }

    public function excluir(string $hash): void
    {
        $arquivo = "templates/assets/arquivos/orcamentos/$hash.pdf";
        if ((new OrcamentoModelo)->excluirOrcamento($hash)) {
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }

            $this->mensagem->mensagemSucesso("Orçamento excluido com sucesso.")->flash();
        }

        Helpers::redirecionar("orcamento/listar");
    }

    public function exibir(string $modelo, string $hash): void
    {
        $dados_objeto = (new OrcamentoModelo)->buscaOrcamentosPorHash($hash)[0];

        $dados_orcamento_json = json_decode($dados_objeto->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados_objeto, $dados_orcamento_json);

        $dados_usuario = $this->orcamentosServicos->separarDadosUsuario($dados_completos);
        $dados_cliente = $this->orcamentosServicos->separarDadosCliente($dados_completos);

        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->orcamentosServicos->processarItensParaView($dados_completos);

        $usuario = (new UsuarioModelo)->buscaPorId($dados_completos['id_usuario']);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $itens_processados,
                'total_orcamento' => $dados_completos['vl_total'],
                'titulo' => $dados_usuario['nome-empresa'],
                'dados_completos' => $dados_completos,
                'usuario' => $usuario,
            ]
        );

        if (Helpers::localhost()) {
            $caminho_local = $_SERVER['DOCUMENT_ROOT'] . '/meus_orcamentos/templates/assets/arquivos/orcamentos/';
        }else {
            $caminho_local = 'templates/assets/arquivos/orcamentos/';
        }
       

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
