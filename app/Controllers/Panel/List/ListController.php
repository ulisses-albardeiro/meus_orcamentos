<?php

namespace App\Controllers\Panel\List;

use App\Controllers\Panel\PanelController;
use App\Core\Helpers;
use App\Services\Company\CompanyInterface;
use App\Services\Listas\ListaInterface;
use App\Services\Orcamentos\OrcamentosInterface;
use App\Services\User\UserInterface;
use App\Adapters\Pdf;
use App\Services\Clients\ClientsInterface;

class ListController extends PanelController
{
    protected ListaInterface $listaServico;
    protected ClientsInterface $clientesServico;
    protected OrcamentosInterface $orcamentoInterface;
    protected UserInterface $usuarioServico;
    protected CompanyInterface $empresaServico;

    public function __construct(
        ListaInterface $listaServico,
        ClientsInterface $clientesServico,
        OrcamentosInterface $orcamentoInterface,
        UserInterface $usuarioServico,
        CompanyInterface $empresaServico
    ) {
        parent::__construct();
        $this->listaServico = $listaServico;
        $this->clientesServico = $clientesServico;
        $this->orcamentoInterface = $orcamentoInterface;
        $this->usuarioServico = $usuarioServico;
        $this->empresaServico = $empresaServico;
    }

    public function index(): void
    {
        $orcamentos = $this->listaServico->buscarListasServico($this->session->userId);
        $clientes = $this->clientesServico->findClientsByUserId($this->session->userId);

        echo $this->template->render(
            "listas/listar.html",
            [
                'listas' => Helpers::attachRelated(
                    $orcamentos,
                    $clientes,
                    'id_cliente',
                    'id',
                    'nome_cliente',
                    'nome'
                ),
                "title" => "Listas",
                'linkAtivo' => 'active',
            ]
        );
    }

    public function templates(): void
    {
        echo $this->template->render(
            "listas/modelos.html",
            [
                "title" => "Modelos"
            ]
        );
    }

    public function create(string $form, string $template): void
    {
        echo $this->template->render(
            "listas/forms/$form.html",
            [
                "title" => "Criar Lista",
                "modelo" => $template,
                "clientes" => $this->clientesServico->findClientsByUserId($this->session->userId) ?? [],
            ]
        );
    }

    public function store(string $template): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (empty($dados['id_cliente'])) {
            $id_cliente = $this->clientesServico->registerClient($dados, $this->session->userId);
        } else {
            $id_cliente = $dados['id_cliente'];
        }

        $hash = Helpers::hash();

        $id_orcamento = $this->listaServico->cadastrarListaServico($dados, $id_cliente, $this->session->userId, $template, $hash);

        if (!empty($id_orcamento)) {
            //redireciona para o método 'exibir'
            Helpers::redirect("listas/$template/$hash");
        }
    }

    public function export(string $template, string $hash): void
    {
        $dados = $this->listaServico->buscaListaPorHashServico($hash);
        $empresa = $this->empresaServico->findCompanyByUserId($dados['id_usuario']);

        $html = $this->template->render(
            "listas/pdf/$template.html",
            [
                "dados" => $dados,
                'empresa' => $empresa,
            ]
        );

        $pdf = new Pdf;
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->baixar("orçamento-" . Helpers::slug($dados['nome_cliente']) . ".pdf");
    }

    public function destroy(string $hash): void
    {
        $arquivo = "templates/assets/arquivos/listas/$hash.pdf";

        if ($this->listaServico->excluirListasServico($hash)) {
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }

            $this->mensagem->mensagemSucesso("Lista excluida com sucesso.")->flash();
        }

        Helpers::redirect("list");
    }

    public function show(string $template, string $hash): void
    {
        $dados = $this->listaServico->buscaListaPorHashServico($hash);

        $empresa = $this->empresaServico->findCompanyByUserId($dados['id_usuario']);

        $html = $this->template->render(
            "listas/pdf/$template.html",
            [
                "dados" => $dados,
                'empresa' => $empresa,
            ]
        );

        if (Helpers::localhost()) {
            $caminho_local = $_SERVER['DOCUMENT_ROOT'] . '/meus_orcamentos/templates/assets/arquivos/listas/';
        } else {
            $caminho_local = 'templates/assets/arquivos/listas/';
        }


        $pdf = new Pdf;
        $pdf->carregarHTML($html);
        $pdf->configurarPapel('A4');
        $pdf->renderizar();
        $pdf->salvarPDF($caminho_local, $hash . '.pdf');

        $lista_url = Helpers::url('templates/assets/arquivos/listas/' . $hash . '.pdf');

        echo $this->template->render(
            "listas/pre-view.html",
            [
                "orcamento" => $lista_url,
                'hash' => $hash,
                'modelo' => $template,
                'empresa' => $empresa,
            ]
        );
    }
}
