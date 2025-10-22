<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\AdminMiddleware;
use sistema\Nucleo\AuthMiddleware;
use sistema\Nucleo\EmpresaMiddleware;
use sistema\Nucleo\Helpers;

try {
    //Site and Blog
    SimpleRouter::setDefaultNamespace('sistema\Controlador');
    SimpleRouter::get(URL, 'SiteController@index');
    SimpleRouter::get(URL . '404', 'SiteController@error404');
    SimpleRouter::get(URL . 'pravicy-policy', 'SiteController@pravicyPolicy');
    SimpleRouter::get(URL . 'blog', 'BlogController@index');

    //Login and Logout
    SimpleRouter::group(['namespace' => 'Login'], function () {
        SimpleRouter::get(URL . 'login', 'LoginController@create');
        SimpleRouter::post(URL . 'login', 'LoginController@store');
        SimpleRouter::get(URL . 'logout', 'LoginController@destroy');
    });

    //Users
    SimpleRouter::group(['namespace' => 'Painel'], function () {
        SimpleRouter::get(URL . 'user', 'UserController@create');
        SimpleRouter::post(URL . 'user', 'UserController@store');
        SimpleRouter::put(URL . 'user/{id}', 'UserController@update')->addMiddleware(AuthMiddleware::class);
        SimpleRouter::patch(URL . 'user/image/{id}', 'UserController@destroyImage')->addMiddleware(AuthMiddleware::class);
    });

    //Password Recovery
    SimpleRouter::group(['namespace' => 'PasswordRecovery'], function () {
        SimpleRouter::get(URL . 'password-recovery', 'PasswordRecoveryController@index');
        SimpleRouter::post(URL . 'password-recovery', 'PasswordRecoveryController@store');
        SimpleRouter::get(URL . 'password-recovery/{token}', 'PasswordRecoveryController@create');
        SimpleRouter::patch(URL . 'password-recovery', 'PasswordRecoveryController@update');
    });

    //Home
    SimpleRouter::group(['namespace' => 'Painel\Home', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        SimpleRouter::get(URL . 'home', 'HomeController@index');
    });

    //Clients
    SimpleRouter::group(['namespace' => 'Painel\Clients', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        SimpleRouter::get(URL . 'clients', 'ClientsController@index');
        SimpleRouter::post(URL . 'clients', 'ClientsController@store');
        SimpleRouter::delete(URL . 'clients/{id}', 'ClientsController@destroy');
        SimpleRouter::put(URL . 'clients/{id}', 'ClientsController@update');
    });

    //Finance
    SimpleRouter::group(['namespace' => 'Painel\Finance', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        //dashboard
        SimpleRouter::get(URL . 'finance/dashboard/{date?}', 'DashboardController@index');

        //Category Finance
        SimpleRouter::post(URL . 'finance/category', 'CategoryController@store');
        SimpleRouter::get(URL . 'finance/category', 'CategoryController@index');
        SimpleRouter::put(URL . 'finance/category/{id}', 'CategoryController@update');
        SimpleRouter::delete(URL . 'finance/category/{id}', 'CategoryController@destroy');

        //Expenses
        SimpleRouter::post(URL . 'finance/expense', 'ExpenseController@store');
        SimpleRouter::get(URL . 'finance/expense', 'ExpenseController@index');
        SimpleRouter::delete(URL . 'finance/expense/{id}', 'ExpenseController@destroy');
        SimpleRouter::put(URL . 'finance/expense/{id}', 'ExpenseController@update');

        //Revenue
        SimpleRouter::post(URL . 'finance/revenue', 'RevenueController@store');
        SimpleRouter::get(URL . 'finance/revenue', 'RevenueController@index');
        SimpleRouter::delete(URL . 'finance/revenue/{id}', 'RevenueController@destroy');
        SimpleRouter::put(URL . 'finance/revenue/{id}', 'RevenueController@update');
    });

    //List
    SimpleRouter::group(['namespace' => 'Painel\List', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        SimpleRouter::get(URL . 'list', 'ListController@index');
        SimpleRouter::get(URL . 'list/templates', 'ListController@templates');
        SimpleRouter::get(URL . 'list/{form}/{template}', 'ListController@create');
        SimpleRouter::post(URL . 'list/{template}', 'ListController@store');
        SimpleRouter::delete(URL . 'list/{hash}', 'ListController@destroy');
    });

    SimpleRouter::group(['namespace' => 'Painel\List'], function () {
        SimpleRouter::get(URL . 'list/{template}/{hash}', 'ListController@show');
        SimpleRouter::get(URL . 'list/pdf/{template}/{hash}', 'ListController@showPdf');
    });

    //Grupo de Rotas Orçamento
    SimpleRouter::group(['namespace' => 'Painel\Orcamento', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        SimpleRouter::get(URL . 'orcamento/modelos', 'OrcamentoControlador@modelos');
        SimpleRouter::get(URL . 'orcamento/excluir/{hash}', 'OrcamentoControlador@excluir');
        SimpleRouter::get(URL . 'orcamento/criar/{form}/{modelo}', 'OrcamentoControlador@criar');
        SimpleRouter::post(URL . 'orcamento/cadastrar/{modelo}', 'OrcamentoControlador@cadastrar');
        SimpleRouter::get(URL . 'orcamento/listar', 'OrcamentoControlador@listar');
    });

    //Grupo de rotas que não precisa de autenticação
    SimpleRouter::group(['namespace' => 'Painel\Orcamento'], function () {
        SimpleRouter::get(URL . 'orcamento/{modelo}/{hash}', 'OrcamentoControlador@exibir');
        SimpleRouter::get(URL . 'orcamento/pdf/{modelo}/{hash}', 'OrcamentoControlador@pdf');
    });

    //Grupo de Rotas Recibo
    SimpleRouter::group(['namespace' => 'Painel\Recibo', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        SimpleRouter::get(URL . 'recibo/gerar', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/gerar/{id_recibo}', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/criar', 'Recibo@criar');
        SimpleRouter::get(URL . 'recibo/listar', 'Recibo@listar');
        SimpleRouter::get(URL . 'recibo/excluir/{id_recibo}', 'Recibo@excluir');
    });

    //Grupo de Rotas Lista
    SimpleRouter::group(['namespace' => 'Painel\Config', 'middleware' => [AuthMiddleware::class, EmpresaMiddleware::class]], function () {
        SimpleRouter::get(URL . 'config', 'ConfigControlador@listar');
        SimpleRouter::get(URL . 'config/excluir/{idUsuario}', 'ConfigControlador@Excluir');
    });

    //Grupo de Rotas Empresa
    SimpleRouter::group(['namespace' => 'Painel\Empresa', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'empresa', 'EmpresaControlador@listar');
        SimpleRouter::post(URL . 'empresa/editar/{id}', 'EmpresaControlador@editar');
        SimpleRouter::match(['get', 'post'], URL . 'empresa/cadastrar', 'EmpresaControlador@cadastrar');
        SimpleRouter::get(URL . 'empresa/excluir/logo', 'EmpresaControlador@excluirLogo');
    });

    //Grupo de Rotas Dashboard
    SimpleRouter::group([
        'namespace' => 'Painel\Admin',
        'middleware' => [
            AdminMiddleware::class,
            AuthMiddleware::class
        ]
    ], function () {
        SimpleRouter::get(URL . 'admin/usuarios', 'Admin@usuarios');
        SimpleRouter::get(URL . 'admin/orcamentos', 'Admin@orcamentos');
        SimpleRouter::get(URL . 'admin/listas', 'Admin@listas');
        SimpleRouter::get(URL . 'admin/recibos', 'Admin@recibos');
    });

    //Grupo de Rotas Dashboard
    SimpleRouter::group([
        'namespace' => 'Painel\Blog',
        'middleware' => [
            AdminMiddleware::class,
            AuthMiddleware::class
        ]
    ], function () {
        SimpleRouter::get(URL . 'admin/categorias', 'BlogControlador@listar');
    });

    SimpleRouter::start();
} catch (Exception $e) {

    if (Helpers::localhost()) {
        echo $e;
    } else {
        Helpers::redirecionar('404');
    }
}
