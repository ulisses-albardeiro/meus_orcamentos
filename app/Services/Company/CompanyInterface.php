<?php

namespace App\Services\Company;

use App\Models\CompanyModel;

interface CompanyInterface
{
    public function registerCompany(array $data, int $userId, ?array $logo): bool;
    public function updateCompany(array $data, int $companyId, ?array $logo): bool;
    public function findCompanyByUserId(int $userId): ?CompanyModel;
    public function destroyLogo(int $companyId): bool;
}
