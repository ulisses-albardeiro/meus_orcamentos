<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Session;

class PanelController extends Controller
{
    protected Session $session;

    public function __construct()
    {
        parent::__construct('templates/views/panel');
        $this->session = (new Session);
    }   
}
