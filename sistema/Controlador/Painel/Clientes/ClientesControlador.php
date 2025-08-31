<?php

namespace sistema\Controlador\Painel\Clientes;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\OrcamentoModelo;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clientes\ClientesInterface;

class ClientesControlador extends PainelControlador
{
    protected ClientesInterface $clientesServico;

    public function __construct(ClientesInterface $clientesServico)
    {
        parent::__construct($this->clientesServico = $clientesServico);
    }

    public function listar(): void
    {
        echo $this->template->rendenizar(
            'clientes/listar.html',
            [
                'titulo' => 'Clientes',
                'clientes' => $this->clientesServico->buscaClientesPorIdUsuarioServico($this->usuario->usuarioId)
            ]
        );
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientesServico->cadastraClientesServico($dados, $this->usuario->usuarioId)) {
            $this->mensagem->mensagemSucesso('Cliente Cadastrado com sucesso.')->flash();
        }

        Helpers::redirecionar('clientes/listar');
    }

    public function excluir(int $id_cliente): void
    {
        if ($this->clientesServico->excluirClientesServico($id_cliente)) {
            $this->mensagem->mensagemSucesso('Cliente excluido com sucesso.')->flash();
        }
        Helpers::redirecionar('clientes/listar');
    }

    public function editar(int $id_cliente): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientesServico->editarClientesServico($dados, $id_cliente)) {
            $this->mensagem->mensagemSucesso('Cliente editado com sucesso.')->flash();
        }
        Helpers::redirecionar('clientes/listar');
    }
}
