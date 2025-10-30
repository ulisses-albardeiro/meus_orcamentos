<?php
namespace app\Servicos\Listas;

use app\Modelos\ListaModelo;

class ListaServicos implements ListaInterface
{
    protected ListaModelo $listaModelo;

    public function __construct(ListaModelo $listaModelo)
    {
        $this->listaModelo = $listaModelo;
    }
    public function cadastrarListaServico(array $dados, int $id_cliente, int $id_usuario, string $modelo, string $hash): bool
    {
        return $this->listaModelo->cadastrarLista($id_cliente, $dados, $id_usuario, $modelo, $hash);
    }

    public function excluirListasServico(string $hash): bool
    {
        return $this->listaModelo->excluirLista($hash);
    }

    public function buscarListasServico(int $id_usuario): ?array
    {
        return $this->listaModelo->buscaListas($id_usuario);
    }

    public function buscaListaPorHashServico(string $hash): array
    {
        $dados = $this->listaModelo->buscaListaPorHash($hash)[0];

        $dados_lista = json_decode($dados->lista_completa, true);
        $dados_completos = array_merge((array) $dados, $dados_lista);
        return $dados_completos;
    }

    public function buscaListaPorIdServico(int $id_lista): ?array
    {
        $dados = $this->listaModelo->buscaListaPorId($id_lista)[0];
        $dados_lista_json = json_decode($dados->orcamento_completo, true);
        $dados_completos = array_merge((array) $dados, $dados_lista_json);

        return $dados_completos;
    }
}