<?php

namespace app\Controlador\Painel\Finance;

use app\Controlador\Painel\PainelControlador;
use app\Nucleo\Helpers;
use app\Servicos\Finance\CategoryInterface;

class CategoryController extends PainelControlador
{
    public function __construct(private CategoryInterface $categoryService)
    {
        parent::__construct();
    }

    public function index(): void
    {
        $categories = $this->categoryService->findCategoryByUserId($this->session->userId);
        echo $this->template->rendenizar(
            "finances/category.html",
            [
                "categories" => $categories,
                "types" => ["Despesas", "Receitas"],
                'titulo' => 'Categoria',
                'categoryMenu' => "active",
            ]
        );
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->categoryService->findCategoryByName($data['name'], $data['type'], $this->session->userId))) {
            $this->mensagem->mensagemAtencao(
                "A categoria '{$data['name']}' do tipo '{$data['type']}' já está cadastrada! Use outro tipo ou nome."
            )->flash();

            Helpers::voltar();
            return;
        }

        if ($this->categoryService->createCategory($data, $this->session->userId)) {
            $this->mensagem->mensagemSucesso("Categoria cadastrada com Sucesso!")->flash();
        }

        Helpers::voltar();
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
