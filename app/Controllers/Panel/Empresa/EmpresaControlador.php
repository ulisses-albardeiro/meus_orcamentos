<?php

namespace App\Controllers\Panel\Empresa;

use App\Controllers\Panel\PanelController;
use App\Core\Helpers;
use App\Services\Company\CompanyInterface;

class EmpresaControlador extends PanelController
{
    protected CompanyInterface $empresaServico;

    public function __construct(CompanyInterface $empresaServico)
    {
        parent::__construct();
        $this->empresaServico = $empresaServico;
    }


    public function listar(): void
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

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            $cadastro = $this->empresaServico->registerCompany($dados, $this->session->userId, $_FILES['logo']);

            if ($cadastro) {
                $this->mensagem->modal('🎉Tudo está pronto!','Gostaria de criar seu primeiro Orçamento? É bem rápido!', Helpers::url('quote/templates'), 'Sim, criar agora')->flash();
            }

            Helpers::redirect('home');
        }

        echo $this->template->render(
            'empresa/cadastro.html',
            [
                "title" => "Configure os dados da sua Empresa",                
            ]
        );
    }

    public function editar(int $id): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $cadastro = $this->empresaServico->updateCompany($dados, $id, $_FILES['logo']);

        if ($cadastro) {
            $this->mensagem->mensagemSucesso('Empresa editada com sucesso.')->flash();
        }

        Helpers::redirect('empresa');
    }

    public function excluirLogo(): void
    {
        $empresa = $this->empresaServico->findCompanyByUserId($this->session->userId);

        unlink($_SERVER['DOCUMENT_ROOT'] . BASE_PATH . 'templates/assets/img/logos/' . $empresa->logo);

        $exclusao = $this->empresaServico->destroyLogo($empresa->id);

        if ($exclusao) {
            $this->mensagem->mensagemSucesso('Logo excluida com sucesso.')->flash();
        }

        Helpers::redirect('empresa');
    }
}
