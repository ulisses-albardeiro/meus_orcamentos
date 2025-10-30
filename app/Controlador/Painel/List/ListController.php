<?php

namespace app\Controlador\Painel\List;

use app\Controlador\Painel\PainelControlador;
use app\Nucleo\Helpers;
use app\Servicos\Empresas\EmpresasInterface;
use app\Servicos\Listas\ListaInterface;
use app\Servicos\Orcamentos\OrcamentosInterface;
use app\Servicos\Usuarios\UsuariosInterface;
use app\Adapter\Pdf;
use app\Servicos\Clients\ClientsInterface;

class ListController extends PainelControlador
{
    protected ListaInterface $listaServico;
    protected ClientsInterface $clientesServico;
    protected OrcamentosInterface $orcamentoInterface;
    protected UsuariosInterface $usuarioServico;
    protected EmpresasInterface $empresaServico;

    public function __construct(
        ListaInterface $listaServico,
        ClientsInterface $clientesServico,
        OrcamentosInterface $orcamentoInterface,
        UsuariosInterface $usuarioServico,
        EmpresasInterface $empresaServico
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

        echo $this->template->rendenizar(
            "listas/listar.html",
            [
                'listas' => Helpers::colocarTodosNomesClientesPeloId($clientes, $orcamentos),
                "titulo" => "Listas",
                'linkAtivo' => 'active',
            ]
        );
    }

    public function templates(): void
    {
        echo $this->template->rendenizar(
            "listas/modelos.html",
            [
                "titulo" => "Modelos"
            ]
        );
    }

    public function create(string $form, string $template): void
    {
        echo $this->template->rendenizar(
            "listas/forms/$form.html",
            [
                "titulo" => "Criar Lista",
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

        $hash = Helpers::gerarHash();

        $id_orcamento = $this->listaServico->cadastrarListaServico($dados, $id_cliente, $this->session->userId, $template, $hash);

        if (!empty($id_orcamento)) {
            //redireciona para o método 'exibir'
            Helpers::redirecionar("listas/$template/$hash");
        }
    }

    public function export(string $template, string $hash): void
    {
        $dados = $this->listaServico->buscaListaPorHashServico($hash);
        $empresa = $this->empresaServico->buscaEmpresaPorIdUsuarioServico($dados['id_usuario']);

        $html = $this->template->rendenizar(
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

        Helpers::redirecionar("list");
    }

    public function show(string $template, string $hash): void
    {
        $dados = $this->listaServico->buscaListaPorHashServico($hash);

        $empresa = $this->empresaServico->buscaEmpresaPorIdUsuarioServico($dados['id_usuario']);

        $html = $this->template->rendenizar(
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

        echo $this->template->rendenizar(
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
