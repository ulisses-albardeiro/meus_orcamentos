<?php

namespace App\Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use App\Models\CompanyModel;
use Pecee\Http\Request;
use App\Core\Helpers;
use App\Core\Session;

class Company implements IMiddleware
{
    public function handle(Request $request): void
    {
        $company = (new CompanyModel)->findCompanyByUserId((new Session)->userId);
        if (empty($company)) {
            Helpers::redirect('company/create');
        }
    }
}
