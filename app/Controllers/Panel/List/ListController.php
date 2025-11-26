<?php

namespace App\Controllers\Panel\List;

use App\Controllers\Panel\PanelController;
use App\Core\Helpers;
use App\Services\Company\CompanyInterface;
use App\Services\Lists\ListInterface;
use App\Adapters\PdfAdapter\PdfInterface;
use App\Services\Clients\ClientsInterface;
use App\Services\Files\FileManagerInterface;

class ListController extends PanelController
{
    public function __construct(
        private ListInterface $listService,
        private ClientsInterface $clientsService,
        private CompanyInterface $empresaServico,
        private FileManagerInterface $fileManager,
        private PdfInterface $pdfGenerator,
    ) {
        parent::__construct();
    }

    public function index(): void
    {
        $list = $this->listService->findListsByUserId($this->session->userId);
        $clients = $this->clientsService->findClientsByUserId($this->session->userId);

        echo $this->template->render(
            "lists/index.html",
            [
                'lists' => Helpers::attachRelated(
                    $list,
                    $clients,
                    'id_cliente',
                    'id',
                    'nome_cliente',
                    'nome'
                ),
                "title" => "Listas",
            ]
        );
    }

    public function templates(): void
    {
        echo $this->template->render(
            "lists/templates.html",
            [
                "title" => "Modelos"
            ]
        );
    }

    public function create(string $form, string $template): void
    {
        echo $this->template->render(
            "lists/forms/$form.html",
            [
                "title" => "Criar Lista",
                "template" => $template,
                "clients" => $this->clientsService->findClientsByUserId($this->session->userId) ?? [],
            ]
        );
    }

    public function store(string $template): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (empty($data['id_cliente'])) {
            $clientId = $this->clientsService->registerClient($data, $this->session->userId);
        } else {
            $clientId = $data['id_cliente'];
        }

        $hash = Helpers::hash();

        $listId = $this->listService->registerList($data, $clientId, $this->session->userId, $template, $hash);

        if (!empty($listId)) {
            //redirect to method 'show'
            Helpers::redirect("list/$template/$hash");
        }
    }

    public function export(string $template, string $hash): void
    {
        $data = $this->listService->findListByHash($hash);
        $company = $this->empresaServico->findCompanyByUserId($data['id_usuario']);

        $html = $this->template->render(
            "lists/pdf/$template.html",
            [
                "data" => $data,
                'company' => $company,
            ]
        );

        $filename = "lista_" . Helpers::slug($data['nome_cliente']) . ".pdf";

        $pdfOutput = $this->pdfGenerator->generate($html, ['chroot' => __DIR__]);
        $this->fileManager->download($pdfOutput, $filename, 'application/pdf');
    }

    public function destroy(string $hash): void
    {
        $file = "templates/assets/arquivos/lists/$hash.pdf";

        if ($this->listService->destroyList($hash)) {
            if (file_exists($file)) {
                unlink($file);
            }

            $this->mensagem->mensagemSucesso("Lista excluida com sucesso.")->flash();
        }

        Helpers::redirect("list");
    }

    public function show(string $template, string $hash): void
    {
        $data = $this->listService->findListByHash($hash);

        $company = $this->empresaServico->findCompanyByUserId($data['id_usuario']);

        $html = $this->template->render(
            "lists/pdf/$template.html",
            [
                "data" => $data,
                'company' => $company,
            ]
        );

        $path = "storage/pdf/user_{$data['id_usuario']}/lists/";

        $pdfOutput = $this->pdfGenerator->generate($html, ['chroot' => __DIR__]);
        $this->fileManager->save($pdfOutput, $path, "$hash.pdf");

        echo $this->template->render(
            "lists/pre-view.html",
            [
                "list" => Helpers::url("$path/$hash.pdf"),
                'hash' => $hash,
                'template' => $template,
                'company' => $company,
            ]
        );
    }
}
