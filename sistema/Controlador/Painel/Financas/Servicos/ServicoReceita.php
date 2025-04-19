<?php
namespace sistema\Controlador\Painel\Financas\Servicos;

final class ServicoReceita
{
    public function getNomeCategoria(array $receitas, array $categorias): array
    {
        $receitas = array_map(function ($receita) use ($categorias) {
            foreach ($categorias as $categoria) {
                if ($receita->id_categoria == $categoria->id) {
                    $receita->categoria = $categoria->nome;
                }
            }
            return $receita;
        }, $receitas);
        return $receitas;
    }
}