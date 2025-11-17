<?php

namespace App\Services\Empresas;

use App\Library\Upload;
use App\Models\CompanyModel;
use App\Core\Helpers;
use App\Core\Model;

class EmpresasServico implements EmpresasInterface
{
    protected CompanyModel $empresaModelo;

    public function __construct(CompanyModel $empresaModelo)
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

        return $this->empresaModelo->registerCompany($dados, $idUsuario, $logo);
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

        return $this->empresaModelo->updateCompany($dados, $idEmpresa, $nomeLogo);
    }

    public function buscaEmpresaPorIdUsuarioServico(int $idUsuario): ?Model
    {
        return $this->empresaModelo->findCompanyByUserId($idUsuario);
    }

    public function excluirLogoServico(int $idEmpresa): bool
    {
        return $this->empresaModelo->destroyLogo($idEmpresa);
    }
}