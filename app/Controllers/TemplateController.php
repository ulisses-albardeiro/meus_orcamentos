<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Core\Model;
use App\Core\Sessao;

final class TemplateController
{
    public static function user(): ?UserModel
    {
        return (new UserModel)
            ->findUserById((new Sessao)->userId);
    }

    public static function company(): ?Model
    {
        return (new CompanyModel)
            ->findCompanyByUserId((new Sessao)->userId);
    }
}
