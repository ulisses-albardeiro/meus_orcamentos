<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Middleware;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');
    SimpleRouter::get(URL, 'SiteControlador@index');
    SimpleRouter::get(URL . '404', 'SiteControlador@erro404');
    SimpleRouter::get(URL . 'politica-de-privacidade', 'SiteControlador@politicaPrivacidade');

    SimpleRouter::group(['namespace' => 'Login'], function () {

        SimpleRouter::match(['get', 'post'], URL . 'login', 'Login@login');
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

        //Modelos
        SimpleRouter::get(URL . 'orcamento/modelos', 'OrcamentoControlador@modelos');

        //Excluir Orçamento
        SimpleRouter::get(URL . 'orcamento/excluir/{id_orcamento}', 'OrcamentoControlador@excluir');

        //Criar Orçamento
        SimpleRouter::get(URL. 'orcamento/criar/{form}/{modelo}', 'OrcamentoControlador@criar');

         //PDF Orçamento
        SimpleRouter::get(URL. 'orcamento/pdf/{modelo}/{id_orcamento}', 'OrcamentoControlador@pdf');

        //Cadastrar Orcamento
        SimpleRouter::post(URL. 'orcamento/cadastrar/{modelo}', 'OrcamentoControlador@cadastrar');

        //Apresenta o Orçamento
        SimpleRouter::get(URL . 'orcamento/{modelo}/{id_orcamento}', 'OrcamentoPublicoControlador@exibir');

        //Listar Meus orcamentos
        SimpleRouter::get(URL. 'orcamento/listar', 'OrcamentoControlador@listar');
    });

    //Grupo de Rotas Recibo
    SimpleRouter::group(['namespace' => 'Painel\Recibo'], function () {

        SimpleRouter::get(URL . 'recibo/gerar', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/gerar/{id_recibo}', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/criar', 'Recibo@criar');
        SimpleRouter::get(URL . 'recibo/listar', 'Recibo@listar');
        SimpleRouter::get(URL . 'recibo/excluir/{id_recibo}', 'Recibo@excluir');
    });

    //Grupo de Rotas Dashboard
    SimpleRouter::group(['namespace' => 'Painel\Admin', 'middleware' => Middleware::class], function () {

        SimpleRouter::get(URL . 'admin/usuarios', 'Admin@usuarios');
        SimpleRouter::get(URL . 'admin/orcamentos', 'Admin@orcamentos');
        SimpleRouter::get(URL . 'admin/listas', 'Admin@listas');
        SimpleRouter::get(URL . 'admin/recibos', 'Admin@recibos');

    });

    SimpleRouter::start();
    
} catch (Exception $e) {

    if (Helpers::localhost()) {
        echo $e;
    } else {
        Helpers::redirecionar('404');
    }
}
