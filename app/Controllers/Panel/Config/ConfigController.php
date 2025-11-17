<?php

namespace App\Controllers\Panel\Config;

use App\Controllers\Panel\PanelController;

class ConfigController extends PanelController
{
    public function index(): void
    {
        echo $this->template->rendenizar(
            'config.html',
            [
                'title' => 'Configurações',
                'subTitle' => 'Suas configurações da conta',
            ]
        );
    }
}
