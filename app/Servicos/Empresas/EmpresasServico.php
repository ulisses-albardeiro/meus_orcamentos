<?php

namespace app\Servicos\Empresas;

use app\Biblioteca\Upload;
use app\Modelos\EmpresasModelo;
use app\Nucleo\Helpers;
use app\Nucleo\Modelo;

class EmpresasServico implements EmpresasInterface
{
    protected EmpresasModelo $empresaModelo;

    public function __construct(EmpresasModelo $empresaModelo)
    {
        $this->empresaModelo = $empresaModelo;
    }

    public function cadastrarEmpresaServico(array $dados, int $idUsuario, ?array $logo): bool
    {
        if (isset($logo)) {
            $arquivo = new Upload('templates/assets/img/');
            $arquivo->arquivo($logo, Helpers::slug($dados['nome']), 'logos');
            $logo = $arquivo->getResultado();
        }

        return $this->empresaModelo->cadastrarEmpresa($dados, $idUsuario, $logo);
    }

    public function editarEmpresaServico(array $dados, int $idEmpresa, ?array $logo): bool
    {
        $nomeLogo = null;
        if (!empty($logo['size'])) {
            $empresa = $this->empresaModelo->buscaPorId($idEmpresa);

            unlink($_SERVER['DOCUMENT_ROOT'].BASE_PATH."templates/assets/img/logos/$empresa->logo");

            $arquivo = new Upload('templates/assets/img/');
            $arquivo->arquivo($logo, Helpers::slug($dados['nome']), 'logos');
            $nomeLogo = $arquivo->getResultado();
        }

        return $this->empresaModelo->editarEmpresa($dados, $idEmpresa, $nomeLogo);
    }

    public function buscaEmpresaPorIdUsuarioServico(int $idUsuario): ?Modelo
    {
        return $this->empresaModelo->buscaEmpresaPorIdUsuario($idUsuario);
    }

    public function excluirLogoServico(int $idEmpresa): bool
    {
        return $this->empresaModelo->excluirLogo($idEmpresa);
    }
}