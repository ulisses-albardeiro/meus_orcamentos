<?php

namespace sistema\Controlador\Painel\Finance;

use sistema\Controlador\Painel\PainelControlador;
use sistema\Nucleo\Helpers;
use sistema\Servicos\Finance\CategoryInterface;
use sistema\Servicos\Finance\ExpenseInterface;

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
        $expenses = $this->serviceExpense->findExpensesByUserId($this->usuario->userId);
        $categoryes = $this->serviceCategory->findCategoryByUserIdAndType($this->usuario->userId, 'Despesas');
        $expenses = Helpers::attachRelated($expenses, $categoryes, 'id_categoria', 'id', 'categoria', 'nome');

        echo $this->template->rendenizar(
            "finances/expenses.html",
            [
                "expenses" => $expenses,
                "categorias" => $categoryes,
                "tipo" => "Despesas",
                'titulo' => 'Despesas',
            ]
        );
    }

    public function store(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ($this->serviceExpense->createExpense($dados, $this->usuario->userId)) {
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
