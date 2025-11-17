<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Core\Model;
use App\Core\Session;

final class TemplateController
{
    public static function user(): ?UserModel
    {
        return (new UserModel)
            ->findUserById((new Session)->userId);
    }

    public static function company(): ?Model
    {
        return (new CompanyModel)
            ->findCompanyByUserId((new Session)->userId);
    }
}
