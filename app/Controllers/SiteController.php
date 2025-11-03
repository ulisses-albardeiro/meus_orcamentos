<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers;
use App\Core\Sessao;

class SiteController extends Controller
{
    public function __construct()
    {
        parent::__construct('templates/views/site');
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            "index.html",
            [
                'home' => 'Home'
            ]
        );
    }

    public function pravicyPolicy(): void
    {
        echo $this->template->rendenizar(
            "pravicy-policy.html",
            [
                'titulo' => 'Política de Privacidade'
            ]
        );
    }

    public function error404(): void
    {
        echo $this->template->rendenizar("404.html", []);
    }
}
