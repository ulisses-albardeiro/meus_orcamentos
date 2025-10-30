<?php

namespace app\Controlador\Painel\Quotes;

use app\Controlador\Painel\PainelControlador;
use app\Modelos\OrcamentoModelo;
use app\Nucleo\Helpers;
use app\Servicos\Empresas\EmpresasInterface;
use app\Servicos\Orcamentos\OrcamentosInterface;
use app\Servicos\Usuarios\UsuariosInterface;
use app\Adapter\PdfAdapter\PdfInterface;
use app\Servicos\Clients\ClientsInterface;
use app\Servicos\Files\FileManagerInterface;

class QuotesController extends PainelControlador
{
    public function __construct(
        private OrcamentosInterface $quoteService,
        private ClientsInterface $clientService,
        private UsuariosInterface $userService,
        private EmpresasInterface $companyService,
        private PdfInterface $pdfGenerator,
        private FileManagerInterface $fileManager,
    ) {
        parent::__construct();
    }

    public function index(): void
    {
        $quotes = $this->quoteService->buscaOrcamentosServico($this->session->userId);
        $clients = $this->clientService->findClientsByUserId($this->session->userId);

        echo $this->template->rendenizar(
            "quotes/index.html",
            [
                'quotes' => Helpers::attachRelated($quotes, $clients, 'id_cliente', 'id', 'nome_cliente', 'nome'),
                "titulo" => "Orçamentos",
                'subTitulo' => 'Seus Orçamentos Gerados',
                'linkAtivo' => 'active',
            ]
        );
    }

    public function templates(): void
    {
        echo $this->template->rendenizar(
            "quotes/templates.html",
            [
                "titulo" => "Templates"
            ]
        );
    }

    public function create(string $form, string $template): void
    {
        echo $this->template->rendenizar(
            "quotes/forms/$form.html",
            [
                "titulo" => "Criar Orçamento",
                "template" => $template,
                "clients" => $this->clientService->findClientsByUserId($this->session->userId) ?? [],
            ]
        );
    }

    public function store(string $template): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (empty($data['id_cliente'])) {
            $clientId = $this->clientService->registerClient($data, $this->session->userId);
        } else {
            $clientId = $data['id_cliente'];
        }

        $totalQuote = $this->quoteService->calcularTotalOrcamento($data);
        $hash = Helpers::gerarHash();

        $quotesId = (new OrcamentoModelo)->cadastrarOrcamento($clientId, $totalQuote, $data, $this->session->userId, $template, $hash);

        if (!empty($quotesId)) {
            //redirect to method 'show'
            Helpers::redirecionar("quote/$template/$hash");
        }
    }

    public function export(string $template, string $hash): void
    {
        $data = $this->quoteService->buscaOrcamentoPorHashServico($hash);

        $dataCompany = $this->quoteService->separarDadosUsuario($data);
        $dataClient = $this->quoteService->separarDadosCliente($data);

        $items = $this->quoteService->processarItensParaView($data);
        $company = $this->companyService->buscaEmpresaPorIdUsuarioServico($data['id_usuario']);

        $html = $this->template->rendenizar(
            "quotes/pdf/$template.html",
            [
                'dataCompany' => $dataCompany,
                'dataClient' => $dataClient,
                'items' => $items,
                'dataQuotes' => $data['vl_total'],
                'fullData' => $data,
                'company' => $company,
            ]
        );

        $filename = "orcamento_" . Helpers::slug($dataClient['nome_cliente']) . ".pdf";

        $pdfOutput = $this->pdfGenerator->generate($html, ['chroot' => __DIR__]);
        $this->fileManager->download($pdfOutput, $filename, 'application/pdf');
    }

    public function destroy(string $hash): void
    {
        $path = "storage/pdf/user_{$this->session->userId}/quotes/$hash.pdf";

        if ($this->quoteService->excluirOrcamentoServico($hash)) {
            $this->fileManager->delete($path);

            $this->mensagem->mensagemSucesso("Orçamento excluido com sucesso!")->flash();
        }

        Helpers::redirecionar("quote");
    }

    public function show(string $template, string $hash): void
    {
        $data = $this->quoteService->buscaOrcamentoPorHashServico($hash);

        $dataCompany = $this->quoteService->separarDadosUsuario($data);
        $dataClient = $this->quoteService->separarDadosCliente($data);

        $items = $this->quoteService->processarItensParaView($data);
        $company = $this->companyService->buscaEmpresaPorIdUsuarioServico($data['id_usuario']);

        $html = $this->template->rendenizar(
            "quotes/pdf/$template.html",
            [
                'dataCompany' => $dataCompany,
                'dataClient' => $dataClient,
                'items' => $items,
                'dataQuotes' => $data['vl_total'],
                'fullData' => $data,
                'company' => $company,
            ]
        );

        $path = "storage/pdf/user_{$data['id_usuario']}/quotes";

        $pdfOutput = $this->pdfGenerator->generate($html, ['chroot' => __DIR__]);
        $this->fileManager->save($pdfOutput, $path, "$hash.pdf");

        echo $this->template->rendenizar(
            "quotes/preview.html",
            [
                "path" => Helpers::url("/storage/pdf/user_{$data['id_usuario']}/quotes/$hash.pdf"),
                'template' => $template,
                'hash' => $hash,
                'titulo' => $dataCompany['nome_empresa'],
                'company' => $company,
            ]
        );
    }
}
