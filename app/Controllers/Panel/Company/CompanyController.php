<?php

namespace App\Controllers\Panel\Company;

use App\Controllers\Panel\PanelController;
use App\Core\Helpers;
use App\Services\Company\CompanyInterface;

class CompanyController extends PanelController
{
    public function __construct(private CompanyInterface $companyService)
    {
        parent::__construct();
    }

    public function index(): void
    {
        echo $this->template->render(
            'company/index.html',
            [
                "title" => "Configure os dados da sua Empresa",
                "subTitle" => "",
            ]
        );
    }

    public function create(): void
    {
        echo $this->template->render(
            'company/register.html',
            [
                "title" => "Configure os dados da sua Empresa",
            ]
        );
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->companyService->registerCompany($data, $this->session->userId, $_FILES['logo'])) {
            $this->mensagem
                ->modal('🎉Tudo está pronto!', 'Gostaria de criar seu primeiro Orçamento? É bem rápido!', Helpers::url('quote/templates'), 'Sim, criar agora')
                ->flash();
        }

        Helpers::redirect('home');
    }

    public function update(int $id): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->companyService->updateCompany($data, $id, $_FILES['logo'])) {
            $this->mensagem
                ->mensagemSucesso('Empresa editada com sucesso.')
                ->flash();
        }

        Helpers::redirect("company");
    }

    public function destroyLogo(): void
    {
        $company = $this->companyService->findCompanyByUserId($this->session->userId);

        unlink($_SERVER['DOCUMENT_ROOT'] . BASE_PATH . 'templates/assets/img/logos/' . $company->logo);

        if ($this->companyService->destroyLogo($company->id)) {
            $this->mensagem->mensagemSucesso('Logo excluida com sucesso.')->flash();
        }

        Helpers::redirect("company");
    }
}
