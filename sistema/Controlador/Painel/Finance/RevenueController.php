<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Finance\CategoryInterface;
use sistema\Servicos\Finance\RevenueInterface;

class RevenueController extends PainelControlador
{
    public function __construct(
        private RevenueInterface $revenueService,
        private CategoryInterface $categoryService
    ) {
        parent::__construct();
    }

    public function index(): void
    {
        $revenues = $this->revenueService->findRevenueByUserId($this->usuario->userId);
        $categories = $this->categoryService->findCategoryByUserIdAndType($this->usuario->userId, 'Receitas');
        $revenues = Helpers::attachRelated($revenues, $categories, 'id_categoria', 'id', 'categoria', 'nome');

        echo $this->template->rendenizar(
            "finances/revenues.html",
            [
                "revenues" => $revenues,
                "categoriesRevenue" => $categories,
                "type" => "Receitas",
                'titulo' => 'Receitas',
                "revenueMenu" => "active",
            ]
        );
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->revenueService->createRevenue($data, $this->usuario->userId)) {
            $this->mensagem->mensagemSucesso("Receita Cadastrada com Sucesso!")->flash();
        }
        Helpers::voltar();
    }

    public function destroy(int $id): void
    {
        if ($this->revenueService->destroyRevenue($id)) {
            $this->mensagem->mensagemSucesso("Receita excluida com sucesso!")->flash();
        }
        Helpers::voltar();
    }

    public function update(int $id): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ($this->revenueService->updateRevenue($data, $id)) {
            $this->mensagem->mensagemSucesso("Receita editada com sucesso!")->flash();
        }
        Helpers::voltar();
    }
}
