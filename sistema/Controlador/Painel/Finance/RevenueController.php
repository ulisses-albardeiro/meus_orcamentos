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
        $revenues = $this->revenueService->findRevenuesByUserId($this->usuario->userId);
        $categories = $this->categoryService->findCategoryByUserId($this->usuario->userId);
        $revenues = Helpers::attachRelated($revenues, $categories, 'id_categoria', 'id', 'categoria', 'nome');

        echo $this->template->rendenizar(
            "finances/revenues.html",
            [
                "receitas" => $revenues,
                "categorias" => $categories,
                "tipo" => "Receitas",
                'titulo' => 'Receitas',
                "revenueMenu" => "active",
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->revenueService->createRevenue($dados, $this->usuario->userId)) {
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
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->revenueService->updateRevenue($dados, $id)) {
            $this->mensagem->mensagemSucesso("Receita editada com sucesso!")->flash();
        }
        Helpers::voltar();
    }
}
