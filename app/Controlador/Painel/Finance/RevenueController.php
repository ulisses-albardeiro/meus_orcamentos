<?php

namespace App\Controlador\Painel\Finance;

use App\Controlador\Painel\PainelControlador;
use App\Nucleo\Helpers;
use App\Servicos\Finance\CategoryInterface;
use App\Servicos\Finance\RevenueInterface;

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
        $revenues = $this->revenueService->findRevenueByUserId($this->session->userId);
        $categories = $this->categoryService->findCategoryByUserIdAndType($this->session->userId, 'Receitas');
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

        if ($this->revenueService->createRevenue($data, $this->session->userId)) {
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
