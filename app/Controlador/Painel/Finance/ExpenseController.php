<?php

namespace app\Controlador\Painel\Finance;

use app\Controlador\Painel\PainelControlador;
use app\Nucleo\Helpers;
use app\Servicos\Finance\CategoryInterface;
use app\Servicos\Finance\ExpenseInterface;

class ExpenseController extends PainelControlador
{
    public function __construct(
        private ExpenseInterface $serviceExpense,
        private CategoryInterface $serviceCategory
    ) {
        parent::__construct();
    }

    public function index(): void
    {
        $expenses = $this->serviceExpense->findExpensesByUserId($this->session->userId);
        $categories = $this->serviceCategory->findCategoryByUserIdAndType($this->session->userId, 'Despesas');
        $expenses = Helpers::attachRelated($expenses, $categories, 'id_categoria', 'id', 'categoria', 'nome');

        echo $this->template->rendenizar(
            "finances/expenses.html",
            [
                "expenses" => $expenses,
                "categoriesExpense" => $categories,
                "type" => "Despesas",
                'titulo' => 'Despesas',
                "expenseMenu" => "active"
            ]
        );
    }

    public function store(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->serviceExpense->createExpense($data, $this->session->userId)) {
            $this->mensagem->mensagemSucesso("Despesa Cadastrada com Sucesso!")->flash();
        }
        Helpers::voltar();
    }

    public function destroy(int $id): void
    {
        if ($this->serviceExpense->destroyExpense($id)) {
            $this->mensagem->mensagemSucesso("Despesa excluida com sucesso!")->flash();
        }
        Helpers::voltar();
    }

    public function update(int $id): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->serviceExpense->updateExpense($dados, $id)) {
            $this->mensagem->mensagemSucesso("Despesa editada com sucesso!")->flash();
        }
        Helpers::voltar();
    }
}
