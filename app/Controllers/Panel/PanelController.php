<?php

namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Core\Sessao;

class PanelController extends Controller
{
    protected Sessao $session;

    public function __construct()
    {
        parent::__construct('templates/views/panel');
        $this->session = (new Sessao);
    }   
}
