<?php

namespace sistema\Controlador\Painel\Financas\Servicos;

final class ServicoDespesa
{
    public function getNomeCategoria(array $despesas, array $categorias): array
    {
        $despesas = array_map(function ($despesa) use ($categorias) {
            foreach ($categorias as $categoria) {
                if ($despesa->id_categoria == $categoria->id) {
                    $despesa->categoria = $categoria->nome;
                }
            }
            return $despesa;
        }, $despesas);
        return $despesas;
    }
}
