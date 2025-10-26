<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Modelos\CategoryModel;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Finance\CategoryInterface;

class CategoryController extends PainelControlador
{
    public function __construct(private CategoryInterface $categoryService)
    {
        parent::__construct();
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->categoryService->findCategoryByName($data['nome'], $data['tipo'], $this->usuario->userId))) {
            $this->mensagem->mensagemAtencao(
                "A categoria '{$data['nome']}' do tipo '{$data['tipo']}' já está cadastrada! Use outro tipo ou nome."
            )->flash();
            Helpers::voltar();
            return;
        }

        if ($this->categoryService->saveCategory($data, $this->usuario->userId)) {
            $this->mensagem->mensagemSucesso("Categoria cadastrada com Sucesso!")->flash();
        }

        Helpers::voltar();
    }

    public function index(): void
    {
        echo $this->template->rendenizar(
            "financas/category.html",
            [
                "categorias" => (new CategoryModel)->getCategorias($this->usuario->userId),
                "tipos" => ["Despesas", "Receitas"],
                'titulo' => 'Categoria'
            ]
        );
    }

    public function update(int $id): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->categoryService->updateCategory($data['nome'], $id)) {
            $this->mensagem->mensagemSucesso("Categoria '{$data['nome']}' editada com Sucesso!")->flash();
        }
        Helpers::voltar();
    }

    public function destroy(int $id): void
    {
        if ($this->categoryService->destroyCategory($id)) {
            $this->mensagem->mensagemSucesso("Categoria excluida com sucesso!")->flash();
        }
        Helpers::voltar();
    }
}
