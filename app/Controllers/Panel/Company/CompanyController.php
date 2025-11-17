<?php

namespace App\Controllers\Panel\Company;

use App\Controllers\Panel\PanelController;
use App\Core\Helpers;
use App\Services\Company\CompanyInterface;

class CompanyController extends PanelController
{
    public function __construct(private CompanyInterface $empresaServico)
    {
        parent::__construct();
    }

    public function index(): void
    {
        echo $this->template->render(
            'empresa/form.html',
            [
                "title" => "Configure os dados da sua Empresa",
                "subTitle" => "",
                'empresa' => $this->empresaServico->findCompanyByUserId($this->session->userId),
            ]
        );
    }

    public function create(): void
    {
        echo $this->template->render(
            'empresa/cadastro.html',
            [
                "title" => "Configure os dados da sua Empresa",
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            $cadastro = $this->empresaServico->registerCompany($dados, $this->session->userId, $_FILES['logo']);

            if ($cadastro) {
                $this->mensagem->modal('🎉Tudo está pronto!', 'Gostaria de criar seu primeiro Orçamento? É bem rápido!', Helpers::url('quote/templates'), 'Sim, criar agora')->flash();
            }

            Helpers::redirect('home');
        }
    }

    public function update(int $id): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $cadastro = $this->empresaServico->updateCompany($dados, $id, $_FILES['logo']);

        if ($cadastro) {
            $this->mensagem->mensagemSucesso('Empresa editada com sucesso.')->flash();
        }

        Helpers::redirect('company');
    }

    public function destroyLogo(): void
    {
        $empresa = $this->empresaServico->findCompanyByUserId($this->session->userId);

        unlink($_SERVER['DOCUMENT_ROOT'] . BASE_PATH . 'templates/assets/img/logos/' . $empresa->logo);

        $exclusao = $this->empresaServico->destroyLogo($empresa->id);

        if ($exclusao) {
            $this->mensagem->mensagemSucesso('Logo excluida com sucesso.')->flash();
        }

        Helpers::redirect('company');
    }
}
