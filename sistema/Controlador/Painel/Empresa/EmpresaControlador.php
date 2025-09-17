<?php

namespace sistema\Controlador\Painel\Empresa;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Empresas\EmpresasInterface;

class EmpresaControlador extends PainelControlador
{
    protected EmpresasInterface $empresaServico;

    public function __construct(EmpresasInterface $empresaServico)
    {
        parent::__construct();
        $this->empresaServico = $empresaServico;
    }


    public function listar() : void
    {
        echo $this->template->rendenizar('empresa/form.html', 
        [
            "titulo" => "Configure os dados da sua Empresa",
            "subTitulo" => "",
            'empresa' => $this->empresaServico->buscaEmpresaPorIdUsuarioServico($this->usuario->usuarioId),
        ]);    
    }

    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $cadastro = $this->empresaServico->cadastrarEmpresaServico($dados, $this->usuario->usuarioId, $_FILES['logo']);

        if ($cadastro) {
            $this->mensagem->mensagemSucesso('Empresa cadastrada com sucesso.')->flash();
        }

        Helpers::redirecionar('empresa');
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

    public function excluirLogo():void 
    {
        $empresa = $this->empresaServico->buscaEmpresaPorIdUsuarioServico($this->usuario->usuarioId);

        unlink($_SERVER['DOCUMENT_ROOT'].URL.'templates/assets/img/logos/'.$empresa->logo);

        $exclusao = $this->empresaServico->excluirLogoServico($empresa->id);

        if ($exclusao) {
            $this->mensagem->mensagemSucesso('Logo excluida com sucesso.')->flash();
        }

        Helpers::redirecionar('empresa');
    }
}