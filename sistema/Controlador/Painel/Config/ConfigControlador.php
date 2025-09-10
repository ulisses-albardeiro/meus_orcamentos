<?php
namespace sistema\Controlador\Painel\Config;

use sistema\Controlador\Painel\PainelControlador;

class ConfigControlador extends PainelControlador
{
    public function listar(): void
    {
        echo $this->template->rendenizar('config.html', 
        [
            'titulo' => 'Configurações',
            'subTitulo' => 'Suas configurações da conta',
        ]);
    }
}