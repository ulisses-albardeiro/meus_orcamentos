<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\Middleware\Admin;
use sistema\Nucleo\Middleware\Auth;
use sistema\Nucleo\Middleware\Company;
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
        SimpleRouter::put(URL . 'user/{id}', 'UserController@update')->addMiddleware(Auth::class);
        SimpleRouter::patch(URL . 'user/image/{id}', 'UserController@destroyImage')->addMiddleware(Auth::class);
    });

    //Password Recovery
    SimpleRouter::group(['namespace' => 'PasswordRecovery'], function () {
        SimpleRouter::get(URL . 'password-recovery', 'PasswordRecoveryController@index');
        SimpleRouter::post(URL . 'password-recovery', 'PasswordRecoveryController@store');
        SimpleRouter::get(URL . 'password-recovery/{token}', 'PasswordRecoveryController@create');
        SimpleRouter::patch(URL . 'password-recovery', 'PasswordRecoveryController@update');
    });

    //Home
    SimpleRouter::group(['namespace' => 'Painel\Home', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get(URL . 'home', 'HomeController@index');
    });

    //Clients
    SimpleRouter::group(['namespace' => 'Painel\Clients', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get(URL . 'clients', 'ClientsController@index');
        SimpleRouter::post(URL . 'clients', 'ClientsController@store');
        SimpleRouter::delete(URL . 'clients/{id}', 'ClientsController@destroy');
        SimpleRouter::put(URL . 'clients/{id}', 'ClientsController@update');
    });

    //Finance
    SimpleRouter::group(['namespace' => 'Painel\Finance', 'middleware' => [Auth::class, Company::class]], function () {
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
    SimpleRouter::group(['namespace' => 'Painel\List', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get(URL . 'list', 'ListController@index');
        SimpleRouter::get(URL . 'list/templates', 'ListController@templates');
        SimpleRouter::get(URL . 'list/{form}/{template}', 'ListController@create');
        SimpleRouter::post(URL . 'list/{template}', 'ListController@store');
        SimpleRouter::delete(URL . 'list/{hash}', 'ListController@destroy');
    });
    //List - public routes
    SimpleRouter::group(['namespace' => 'Painel\List'], function () {
        SimpleRouter::get(URL . 'list/{template}/{hash}', 'ListController@show');
        SimpleRouter::get(URL . 'list/pdf/{template}/{hash}', 'ListController@export');
    });

    //Quotes
    SimpleRouter::group(['namespace' => 'Painel\Quotes', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get(URL . 'quote/templates', 'QuotesController@templates');
        SimpleRouter::delete(URL . 'quote/{hash}', 'QuotesController@destroy');
        SimpleRouter::get(URL . 'quote/{form}/{template}', 'QuotesController@create');
        SimpleRouter::post(URL . 'quote/{template}', 'QuotesController@store');
        SimpleRouter::get(URL . 'quote', 'QuotesController@index');
    });
    //Quote - public routes
    SimpleRouter::group(['namespace' => 'Painel\Quotes'], function () {
        SimpleRouter::get(URL . 'quote/{template}/{hash}', 'QuotesController@show');
        SimpleRouter::get(URL . 'quote/pdf/{template}/{hash}', 'QuotesController@export');
    });

    //Grupo de Rotas Recibo
    SimpleRouter::group(['namespace' => 'Painel\Recibo', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get(URL . 'recibo/gerar', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/gerar/{id_recibo}', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/criar', 'Recibo@criar');
        SimpleRouter::get(URL . 'recibo/listar', 'Recibo@listar');
        SimpleRouter::get(URL . 'recibo/excluir/{id_recibo}', 'Recibo@excluir');
    });

    //Grupo de Rotas Lista
    SimpleRouter::group(['namespace' => 'Painel\Config', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get(URL . 'config', 'ConfigControlador@listar');
        SimpleRouter::get(URL . 'config/excluir/{idUsuario}', 'ConfigControlador@Excluir');
    });

    //Grupo de Rotas Empresa
    SimpleRouter::group(['namespace' => 'Painel\Empresa', 'middleware' => Auth::class], function () {
        SimpleRouter::get(URL . 'empresa', 'EmpresaControlador@listar');
        SimpleRouter::post(URL . 'empresa/editar/{id}', 'EmpresaControlador@editar');
        SimpleRouter::match(['get', 'post'], URL . 'empresa/cadastrar', 'EmpresaControlador@cadastrar');
        SimpleRouter::get(URL . 'empresa/excluir/logo', 'EmpresaControlador@excluirLogo');
    });

    //Grupo de Rotas Dashboard
    SimpleRouter::group([
        'namespace' => 'Painel\Admin',
        'middleware' => [
            Admin::class,
            Auth::class
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
            Admin::class,
            Auth::class
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
