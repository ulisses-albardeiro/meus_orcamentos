<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers;
use App\Core\Session;

class SiteController extends Controller
{
    public function __construct()
    {
        parent::__construct('templates/views/site');
    }

    public function index(): void
    {
        echo $this->template->render(
            "index.html",
            [
                'home' => 'Home'
            ]
        );
    }

    public function pravicyPolicy(): void
    {
        echo $this->template->render(
            "pravicy-policy.html",
            [
                'title' => 'Política de Privacidade'
            ]
        );
    }

    public function error404(): void
    {
        echo $this->template->render("404.html", []);
    }
}
