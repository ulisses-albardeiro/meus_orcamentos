<?php

namespace App\Services\Company;

use App\Library\Upload;
use App\Models\CompanyModel;
use App\Core\Helpers;

class CompanyService implements CompanyInterface
{
    public function __construct(private CompanyModel $companyModel) {}

    public function registerCompany(array $data, int $userId, ?array $logo): bool
    {
        if (isset($logo)) {
            $file = new Upload('templates/assets/img/');
            $file->arquivo($logo, Helpers::slug($data['nome']), 'logos');
            $logo = $file->getResultado();
        }

        return $this->companyModel->registerCompany($data, $userId, $logo);
    }

    public function updateCompany(array $data, int $companyId, ?array $logo): bool
    {
        $nomeLogo = null;
        if (!empty($logo['size'])) {
            $empresa = $this->companyModel->buscaPorId($companyId);

            unlink($_SERVER['DOCUMENT_ROOT'] . BASE_PATH . "templates/assets/img/logos/$empresa->logo");

            $file = new Upload('templates/assets/img/');
            $file->arquivo($logo, Helpers::slug($data['nome']), 'logos');
            $nomeLogo = $file->getResultado();
        }

        return $this->companyModel->updateCompany($data, $companyId, $nomeLogo);
    }

    public function findCompanyByUserId(int $userId): ?CompanyModel
    {
        return $this->companyModel->findCompanyByUserId($userId);
    }

    public function destroyLogo(int $companyId): bool
    {
        return $this->companyModel->destroyLogo($companyId);
    }
}
