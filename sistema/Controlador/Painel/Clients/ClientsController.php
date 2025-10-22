<?php

namespace sistema\Controlador\Painel\Clients;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clientes\ClientesInterface;

class ClientsController extends PainelControlador
{
    protected ClientesInterface $clientesServico;

    public function __construct(ClientesInterface $clientesServico)
    {
        parent::__construct($this->clientesServico = $clientesServico);
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            'clients/index.html',
            [
                'titulo' => 'Clientes',
                'clientes' => $this->clientesServico->buscaClientesPorIdUsuarioServico($this->usuario->usuarioId)
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientesServico->cadastraClientesServico($dados, $this->usuario->usuarioId)) {
            $this->mensagem->mensagemSucesso('Cliente Cadastrado com sucesso.')->flash();
        }

        Helpers::redirecionar('clients');
    }

    public function destroy(int $id): void
    {
        if ($this->clientesServico->excluirClientesServico($id)) {
            $this->mensagem->mensagemSucesso('Cliente excluido com sucesso.')->flash();
        }
        Helpers::redirecionar('clients');
    }

    public function update(int $id): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientesServico->editarClientesServico($dados, $id)) {
            $this->mensagem->mensagemSucesso('Cliente editado com sucesso.')->flash();
        }
        Helpers::redirecionar('clients');
    }
}
