<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Empresas\EmpresasInterface;
use sistema\Servicos\Orcamentos\OrcamentosInterface;
use sistema\Servicos\Usuarios\UsuariosInterface;
use sistema\Adapter\Pdf;
use sistema\Adapter\PdfAdapter\PDFInterface;
use sistema\Servicos\Clients\ClientsInterface;

class OrcamentoControlador extends PainelControlador
{
    protected OrcamentosInterface $orcamentosServicos;
    protected ClientsInterface $clientesServico;
    protected UsuariosInterface $usuarioServico;
    protected EmpresasInterface $empresaServico;
    protected PDFInterface $pdfService;

    public function __construct(OrcamentosInterface $orcamentosServicos, ClientsInterface $clientesServico, UsuariosInterface $usuarioServico, EmpresasInterface $empresaServico, PDFInterface $pdfService)
    {
        parent::__construct();
        $this->orcamentosServicos = $orcamentosServicos;
        $this->clientesServico = $clientesServico;
        $this->usuarioServico = $usuarioServico;
        $this->empresaServico = $empresaServico;
        $this->pdfService = $pdfService;
    }

    public function listar(): void
    {
        $orcamentos = $this->orcamentosServicos->buscaOrcamentosServico($this->usuario->userId);
        $clientes = $this->clientesServico->findClientsByUserId($this->usuario->userId);

        echo $this->template->rendenizar(
            "orcamentos/listar.html",
            [
                'orcamentos' => Helpers::colocarTodosNomesClientesPeloId($clientes, $orcamentos),
                "titulo" => "Orçamentos",
                'subTitulo' => 'Seus Orçamentos Gerados',
                'linkAtivo' => 'active',
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
                "clientes" => $this->clientesServico->findClientsByUserId($this->usuario->userId) ?? [],
            ]
        );
    }

    public function cadastrar(string $modelo): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (empty($dados['id_cliente'])) {
            $id_cliente = $this->clientesServico->registerClient($dados, $this->usuario->userId);
        } else {
            $id_cliente = $dados['id_cliente'];
        }

        $total_orcamento = $this->orcamentosServicos->calcularTotalOrcamento($dados);
        $hash = Helpers::gerarHash();

        $id_orcamento = (new OrcamentoModelo)->cadastrarOrcamento($id_cliente, $total_orcamento, $dados, $this->usuario->userId, $modelo, $hash);

        if (!empty($id_orcamento)) {
            //redireciona para o método 'exibir'
            Helpers::redirecionar("orcamento/$modelo/$hash");
        }
    }

    public function pdf(string $modelo, string $hash): void
    {
        $dados = $this->orcamentosServicos->buscaOrcamentoPorHashServico($hash);

        $dados_empresa = $this->orcamentosServicos->separarDadosUsuario($dados);
        $dados_cliente = $this->orcamentosServicos->separarDadosCliente($dados);

        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->orcamentosServicos->processarItensParaView($dados);
        $empresa = $this->empresaServico->buscaEmpresaPorIdUsuarioServico($dados['id_usuario']);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_empresa' => $dados_empresa,
                'dados_cliente' => $dados_cliente,
                'itens' => $itens_processados,
                'total_orcamento' => $dados['vl_total'],
                'dados_completos' => $dados,
                'empresa' => $empresa,
            ]
        );

        $filename = "orcamento-" . Helpers::slug($dados_cliente['nome_cliente']) . ".pdf";

        $pdfOutput = $this->pdfService->generatePDF($html, ['chroot' => __DIR__]);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $pdfOutput;
        exit;
    }

    public function excluir(string $hash): void
    {
        $arquivo = "templates/assets/arquivos/orcamentos/$hash.pdf";
        if ($this->orcamentosServicos->excluirOrcamentoServico($hash)) {
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }

            $this->mensagem->mensagemSucesso("Orçamento excluido com sucesso.")->flash();
        }

        Helpers::redirecionar("orcamento/listar");
    }

    public function exibir(string $modelo, string $hash): void
    {
        $dados = $this->orcamentosServicos->buscaOrcamentoPorHashServico($hash);

        $dados_empresa = $this->orcamentosServicos->separarDadosUsuario($dados);
        $dados_cliente = $this->orcamentosServicos->separarDadosCliente($dados);

        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->orcamentosServicos->processarItensParaView($dados);
        $empresa = $this->empresaServico->buscaEmpresaPorIdUsuarioServico($dados['id_usuario']);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_empresa' => $dados_empresa,
                'dados_cliente' => $dados_cliente,
                'itens' => $itens_processados,
                'total_orcamento' => $dados['vl_total'],
                'dados_completos' => $dados,
                'empresa' => $empresa,
            ]
        );

        if (Helpers::localhost()) {
            $caminho_local = $_SERVER['DOCUMENT_ROOT'] . '/meus_orcamentos/templates/assets/arquivos/orcamentos/';
        } else {
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
                "id_orcamento" => $dados['id'],
                'modelo' => $modelo,
                'hash' => $hash,
                'titulo' => $dados_empresa['nome_empresa'],
                'empresa' => $empresa,
            ]
        );
    }
}
