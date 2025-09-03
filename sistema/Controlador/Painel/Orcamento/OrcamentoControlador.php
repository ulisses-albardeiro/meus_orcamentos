<?php

namespace sistema\Controlador\Painel\Orcamento;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clientes\ClientesInterface;
use sistema\Servicos\Orcamentos\OrcamentosInterface;
use sistema\Servicos\Usuarios\UsuariosInterface;
use sistema\Suporte\Pdf;

class OrcamentoControlador extends PainelControlador
{
    protected OrcamentosInterface $orcamentosServicos;
    protected ClientesInterface $clientesServico;
    protected UsuariosInterface $usuarioServico;

    public function __construct(OrcamentosInterface $orcamentosServicos, ClientesInterface $clientesServico, UsuariosInterface $usuarioServico)
    {
        parent::__construct();
        $this->orcamentosServicos = $orcamentosServicos;
        $this->clientesServico = $clientesServico;
        $this->usuarioServico = $usuarioServico;
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
                "clientes" => $this->clientesServico->buscaClientesPorIdUsuarioServico($this->usuario->usuarioId) ?? [],
            ]
        );
    }

    public function cadastrar(string $modelo): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (empty($dados['id_cliente'])) {
            $id_cliente = $this->clientesServico->cadastraClientesServico($dados, $this->usuario->usuarioId);
        } else {
            $id_cliente = $dados['id_cliente'];
        }

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
        $dados = $this->orcamentosServicos->buscaOrcamentoPorIdServico($id_orcamento);

        $dados_usuario = $this->orcamentosServicos->separarDadosUsuario($dados);
        $dados_cliente = $this->orcamentosServicos->separarDadosCliente($dados);

        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->orcamentosServicos->processarItensParaView($dados);

         $usuario = $this->usuarioServico->buscaUsuariosPorIdServico($dados['id_usuario']);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $itens_processados,
                'total_orcamento' => $dados['vl_total'],
                'titulo' => $dados_usuario['nome-empresa'],
                'dados_completos' => $dados,
                'usuario' => $usuario[0],
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

        $dados_usuario = $this->orcamentosServicos->separarDadosUsuario($dados);
        $dados_cliente = $this->orcamentosServicos->separarDadosCliente($dados);

        // Processa os itens para ter valores numéricos limpos
        $itens_processados = $this->orcamentosServicos->processarItensParaView($dados);

        $usuario = $this->usuarioServico->buscaUsuariosPorIdServico($dados['id_usuario']);

        $html = $this->template->rendenizar(
            "orcamentos/pdf/$modelo.html",
            [
                'dados_usuario' => $dados_usuario,
                'dados_cliente' => $dados_cliente,
                'itens' => $itens_processados,
                'total_orcamento' => $dados['vl_total'],
                'titulo' => $dados_usuario['nome-empresa'],
                'dados_completos' => $dados,
                'usuario' => $usuario[0],
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
                'usuario' => $usuario[0],
                'hash' => $hash,
            ]
        );
    }
}
