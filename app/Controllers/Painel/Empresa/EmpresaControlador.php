<?php

namespace App\Controllers\Painel\Empresa;

use App\Controllers\Painel\PainelControlador;
use App\Core\Helpers;
use App\Services\Empresas\EmpresasInterface;

class EmpresaControlador extends PainelControlador
{
    protected EmpresasInterface $empresaServico;

    public function __construct(EmpresasInterface $empresaServico)
    {
        parent::__construct();
        $this->empresaServico = $empresaServico;
    }


    public function listar(): void
    {
        echo $this->template->rendenizar(
            'empresa/form.html',
            [
                "titulo" => "Configure os dados da sua Empresa",
                "subTitulo" => "",
                'empresa' => $this->empresaServico->buscaEmpresaPorIdUsuarioServico($this->session->userId),
            ]
        );
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            $cadastro = $this->empresaServico->cadastrarEmpresaServico($dados, $this->session->userId, $_FILES['logo']);

            if ($cadastro) {
                $this->mensagem->modal('🎉Tudo está pronto!','Gostaria de criar seu primeiro Orçamento? É bem rápido!', Helpers::url('quote/templates'), 'Sim, criar agora')->flash();
            }

            Helpers::redirecionar('home');
        }

        echo $this->template->rendenizar(
            'empresa/cadastro.html',
            [
                "titulo" => "Configure os dados da sua Empresa",                
            ]
        );
    }

    public function editar(int $id): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $cadastro = $this->empresaServico->editarEmpresaServico($dados, $id, $_FILES['logo']);

        if ($cadastro) {
            $this->mensagem->mensagemSucesso('Empresa editada com sucesso.')->flash();
        }

        Helpers::redirecionar('empresa');
    }

    public function excluirLogo(): void
    {
        $empresa = $this->empresaServico->buscaEmpresaPorIdUsuarioServico($this->session->userId);

        unlink($_SERVER['DOCUMENT_ROOT'] . BASE_PATH . 'templates/assets/img/logos/' . $empresa->logo);

        $exclusao = $this->empresaServico->excluirLogoServico($empresa->id);

        if ($exclusao) {
            $this->mensagem->mensagemSucesso('Logo excluida com sucesso.')->flash();
        }

        Helpers::redirecionar('empresa');
    }
}
