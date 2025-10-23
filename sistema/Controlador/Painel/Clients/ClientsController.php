<?php

namespace sistema\Controlador\Painel\Clients;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Clients\ClientsInterface;

class ClientsController extends PainelControlador
{
    protected ClientsInterface $clientService;

    public function __construct(ClientsInterface $clientService)
    {
        parent::__construct($this->clientService = $clientService);
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            'clients/index.html',
            [
                'titulo' => 'Clientes',
                'clientes' => $this->clientService->findClientsByUserId($this->usuario->userId)
            ]
        );
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientService->registerClient($data, $this->usuario->userId)) {
            $this->mensagem->mensagemSucesso('Cliente Cadastrado com sucesso.')->flash();
        }

        Helpers::redirecionar('clients');
    }

    public function destroy(int $id): void
    {
        if ($this->clientService->destroyClient($id)) {
            $this->mensagem->mensagemSucesso('Cliente excluido com sucesso.')->flash();
        }
        Helpers::redirecionar('clients');
    }

    public function update(int $id): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientService->updateClient($data, $id)) {
            $this->mensagem->mensagemSucesso('Cliente editado com sucesso.')->flash();
        }
        Helpers::redirecionar('clients');
    }
}
