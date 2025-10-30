<?php

namespace app\Controlador\Painel\Clients;

use app\Controlador\Painel\PainelControlador;
use app\Nucleo\Helpers;
use app\Servicos\Clients\ClientsInterface;

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
