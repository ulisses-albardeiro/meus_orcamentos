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

     //Grupo de Rotas Home
     SimpleRouter::group(['namespace' => 'Painel\Financas'], function () {
        //dashboard
        SimpleRouter::get(URL. 'dashboard-financas', 'Dashboard@listar');
        //Nova Categoria
        SimpleRouter::post(URL. 'add-categoria', 'Categoria@cadastrar');
        //Nova Despesa
        SimpleRouter::post(URL. 'add-despesa', 'Despesa@cadastrar');
        //Nova Receita
        SimpleRouter::post(URL. 'add-receita', 'Receita@cadastrar');
     });

       //Grupo de Rotas Lista
       SimpleRouter::group(['namespace' => 'Painel\Lista'], function () {
        //Criar lista
        SimpleRouter::get(URL. 'criar-lista', 'GerarLista@criar');
        SimpleRouter::match(['get', 'post'], URL . 'gerar-lista', 'GerarLista@gerar');
        SimpleRouter::get(URL. 'minhas-listas', 'MinhasListas@listar');
        SimpleRouter::get(URL. 'ver-lista/{id_lista}', 'VerLista@gerar');
        SimpleRouter::match(['get', 'post'], URL . 'excluir-lista/{id_lista}', 'ExcluirLista@excluir');
     });

    //Grupo de Rotas Orçamento
    SimpleRouter::group(['namespace' => 'Painel\Orcamento'], function () {

        //Home
        SimpleRouter::get(URL. 'home', 'Home@listar');

        //Cadastro novos usuários
        SimpleRouter::get(URL. 'form-cadastro', 'CadastroUsuario@form');
        SimpleRouter::post(URL . 'cadastrar-usuario', 'CadastroUsuario@cadastrar');

        //Criar orçamento
        SimpleRouter::get(URL. 'novo-orcamento', 'GerarOrcamento@criar');
        SimpleRouter::match(['get', 'post'], URL . 'gerar-orcamento', 'GerarOrcamento@gerar');

        //Excluir Orçamento
        SimpleRouter::match(['get', 'post'], URL . 'excluir-orcamento/{id_orcamento}', 'ExcluirOrcamento@excluir');

        //Listar Meus orcamentos
        SimpleRouter::get(URL. 'meus-orcamentos', 'MeusOrcamentos@listar');

        //Ver orcamento
        SimpleRouter::get(URL. 'ver-orcamento/{id_orcamento}', 'VerOrcamento@gerar');

    });

    SimpleRouter::start();
} catch (Exception $e) {

    if (Helpers::localhost()) {
        echo $e;
    } else {
        Helpers::redirecionar('404');
    }
}
