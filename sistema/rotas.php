<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');
    SimpleRouter::get(URL, 'SiteControlador@index');
    SimpleRouter::get(URL . '404', 'SiteControlador@erro404');

    SimpleRouter::group(['namespace' => 'Login'], function () {

        //Rota para a página de login do painel
        SimpleRouter::match(['get', 'post'], URL . 'login', 'Login@login');

        //Logout
        SimpleRouter::get(URL . 'sair', 'Login@sair');
    }); 

    SimpleRouter::group(['namespace' => 'Painel'], function () {

        //Cadastro novos usuários
        SimpleRouter::get(URL. 'form-cadastro', 'CadastroUsuario@form');
        SimpleRouter::post(URL . 'cadastrar-usuario', 'CadastroUsuario@cadastrar');

        //Listagem e Edição de perfil
        SimpleRouter::get(URL. 'perfil', 'Perfil@listar');
        SimpleRouter::post(URL. 'perfil-editar', 'Perfil@editar');
        SimpleRouter::get(URL. 'remover-logo', 'Perfil@removerLogo');

    });  

    //Grupo de Rotas Recuperação de senha
    SimpleRouter::group(['namespace' => 'ReculperarSenha'], function () {

        SimpleRouter::match(['get', 'post'], URL . 'recuperacao-de-senha', 'EmaiRecuperacao@emaiRecuperacao');
        SimpleRouter::match(['get', 'post'], URL . 'nova-senha/', 'NovaSenha@novaSenha');
        SimpleRouter::match(['get', 'post'], URL . 'salvar-senha', 'NovaSenha@salvarNovaSenha');
    });

     //Grupo de Rotas Home
     SimpleRouter::group(['namespace' => 'Painel\Home'], function () {
        //Home
        SimpleRouter::get(URL. 'home', 'Home@listar');
     });

     //Grupo de Rotas Finanças
     SimpleRouter::group(['namespace' => 'Painel\Financas'], function () {
        //dashboard
        SimpleRouter::match(["get", "post"], URL. 'dashboard-financas', 'Dashboard@listar');
        //Nova Categoria
        SimpleRouter::post(URL. 'add-categoria', 'Categoria@cadastrar');
        SimpleRouter::get(URL. 'categoria', 'Categoria@listar');
        SimpleRouter::match(['get', 'post'], URL. 'editar-categoria/{id_categoria}', 'Categoria@editar');
        SimpleRouter::get(URL. 'excluir-categoria/{id_categoria}', 'Categoria@excluir');

        //Despesas
        SimpleRouter::post(URL. 'add-despesa', 'Despesa@cadastrar');
        SimpleRouter::get(URL. 'despesas', 'Despesa@listar');
        SimpleRouter::get(URL. 'excluir-despesa/{id_despesa}', 'Despesa@excluir');
        SimpleRouter::match(['get', 'post'], URL. 'editar-despesa/{id_despesa}', 'Despesa@editar');

        //Receitas
        SimpleRouter::post(URL. 'add-receita', 'Receita@cadastrar');
        SimpleRouter::get(URL. 'receitas', 'Receita@listar');
        SimpleRouter::get(URL. 'excluir-receita/{id_receita}', 'Receita@excluir');
        SimpleRouter::match(['get', 'post'], URL. 'editar-receita/{id_receita}', 'Receita@editar');
     });

       //Grupo de Rotas Lista
       SimpleRouter::group(['namespace' => 'Painel\Lista'], function () {
        //Criar lista
        SimpleRouter::get(URL. 'criar-lista', 'Lista@cadastrar');
        //Gerar Lista
        SimpleRouter::match(['get', 'post'], URL . 'gerar-lista', 'GerarLista@gerar');
        //Listar Listas
        SimpleRouter::get(URL. 'minhas-listas', 'Lista@listar');
        //Ver Lista
        SimpleRouter::get(URL. 'ver-lista/{id_lista}', 'GerarLista@gerar');
        //Excluir Lista
        SimpleRouter::match(['get', 'post'], URL . 'excluir-lista/{id_lista}', 'Lista@excluir');
     });

    //Grupo de Rotas Orçamento
    SimpleRouter::group(['namespace' => 'Painel\Orcamento'], function () {

        //Home
        SimpleRouter::get(URL. 'home', 'Home@listar');

        //Cadastro novos usuários
        SimpleRouter::get(URL. 'form-cadastro', 'CadastroUsuario@form');
        SimpleRouter::post(URL . 'cadastrar-usuario', 'CadastroUsuario@cadastrar');

        //Criar orçamento
        SimpleRouter::get(URL. 'modelos-de-orcamentos', 'Orcamento@modelos');
        SimpleRouter::get(URL. 'novo-orcamento-detalhado', 'Orcamento@formDetalhado');
        SimpleRouter::get(URL. 'novo-orcamento-simples', 'Orcamento@formSimples');
        SimpleRouter::match(['get', 'post'], URL . 'gerar-orcamento', 'GerarOrcamento_1@gerar');
        SimpleRouter::match(['get', 'post'], URL . 'gerar-orcamento-simples', 'GerarOrcamento_2@gerar');

        //Excluir Orçamento
        SimpleRouter::match(['get', 'post'], URL . 'excluir-orcamento/{id_orcamento}', 'Orcamento@excluir');

        //Listar Meus orcamentos
        SimpleRouter::get(URL. 'meus-orcamentos', 'Orcamento@listar');

        //Ver orcamento
        SimpleRouter::get(URL. 'ver-orcamento/{id_orcamento}', 'GerarOrcamento_1@gerar');
        SimpleRouter::get(URL. 'ver-orcamento-simples/{id_orcamento}', 'GerarOrcamento_2@gerar');

    });

    //Grupo de Rotas Recibo
    SimpleRouter::group(['namespace' => 'Painel\Recibo'], function () {

        SimpleRouter::get(URL . 'recibo/gerar', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/gerar/{id_recibo}', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/criar', 'Recibo@criar');
        SimpleRouter::get(URL . 'recibo/listar', 'Recibo@listar');
        SimpleRouter::get(URL . 'recibo/excluir/{id_recibo}', 'Recibo@excluir');
    });

    SimpleRouter::start();
} catch (Exception $e) {

    if (Helpers::localhost()) {
        echo $e;
    } else {
        Helpers::redirecionar('404');
    }
}
