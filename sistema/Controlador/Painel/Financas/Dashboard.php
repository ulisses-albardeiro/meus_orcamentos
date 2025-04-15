<?php

namespace sistema\Controlador\Painel\Financas;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoriaModelo;
use sistema\Modelos\DespesaModelo;
use sistema\Modelos\ReceitaModelo;

class Dashboard extends PainelControlador
{
    public function listar() : void
    {
        echo $this->template->rendenizar("financas/dashboard.html", 
        [
            "categorias" => (new CategoriaModelo)->busca()->resultado(true),
            "receita_total" => $this->somarReceita(),
            "despesas_total" => $this->somarDespesa()
        ]);    
    }

    private function somarReceita()
    {
        $receitas = (new ReceitaModelo)->busca(null, null, 'valor')->resultado(true);  
        return array_sum(array_column($receitas, 'valor'))/100;  
    }

    private function somarDespesa()
    {
        $despesa = (new DespesaModelo)->busca(null, null, 'valor')->resultado(true);  
        return array_sum(array_column($despesa, 'valor'))/100;  
    }
}