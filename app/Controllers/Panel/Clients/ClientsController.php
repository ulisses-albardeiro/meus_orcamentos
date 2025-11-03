<?php

namespace App\Controllers\Panel\Clients;

use App\Controllers\Panel\PanelController;
use App\Core\Helpers;
use App\Services\Clients\ClientsInterface;

class ClientsController extends PanelController
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
                'clientes' => $this->clientService->findClientsByUserId($this->session->userId)
            ]
        );
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->clientService->registerClient($data, $this->session->userId)) {
            $this->mensagem->mensagemSucesso('Cliente Cadastrado com sucesso!')->flash();
        }

        Helpers::voltar();
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
