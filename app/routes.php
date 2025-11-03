<?php

use Pecee\SimpleRouter\SimpleRouter;
use App\Core\Middleware\Admin;
use App\Core\Middleware\Auth;
use App\Core\Middleware\Company;
use App\Core\Helpers;

try {
    //Site and Blog
    SimpleRouter::setDefaultNamespace('App\Controllers');
    SimpleRouter::get(BASE_PATH, 'SiteController@index');
    SimpleRouter::get('404', 'SiteController@error404');
    SimpleRouter::get('pravicy-policy', 'SiteController@pravicyPolicy');
    SimpleRouter::get('blog', 'BlogController@index');

    //Login and Logout
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Login'], function () {
        SimpleRouter::get('login', 'LoginController@create');
        SimpleRouter::post('login', 'LoginController@store');
        SimpleRouter::get('logout', 'LoginController@destroy');
    });

    //Users
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel'], function () {
        SimpleRouter::get('user', 'UserController@create');
        SimpleRouter::post('user', 'UserController@store');
        SimpleRouter::put('user/{id}', 'UserController@update')->addMiddleware(Auth::class);
        SimpleRouter::patch('user/image/{id}', 'UserController@destroyImage')->addMiddleware(Auth::class);
    });

    //Password Recovery
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'PasswordRecovery'], function () {
        SimpleRouter::get('password-recovery', 'PasswordRecoveryController@index');
        SimpleRouter::post('password-recovery', 'PasswordRecoveryController@store');
        SimpleRouter::get('password-recovery/{token}', 'PasswordRecoveryController@create');
        SimpleRouter::patch('password-recovery', 'PasswordRecoveryController@update');
    });

    //Home
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Home', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get('home', 'HomeController@index');
    });

    //Clients
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Clients', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get('clients', 'ClientsController@index');
        SimpleRouter::post('clients', 'ClientsController@store');
        SimpleRouter::delete('clients/{id}', 'ClientsController@destroy');
        SimpleRouter::put('clients/{id}', 'ClientsController@update');
    });

    //Finance
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Finance', 'middleware' => [Auth::class, Company::class]], function () {
        //dashboard
        SimpleRouter::get('finance/dashboard/{date?}', 'DashboardController@index');

        //Category Finance
        SimpleRouter::post('finance/category', 'CategoryController@store');
        SimpleRouter::get('finance/category', 'CategoryController@index');
        SimpleRouter::put('finance/category/{id}', 'CategoryController@update');
        SimpleRouter::delete('finance/category/{id}', 'CategoryController@destroy');

        //Expenses
        SimpleRouter::post('finance/expense', 'ExpenseController@store');
        SimpleRouter::get('finance/expense', 'ExpenseController@index');
        SimpleRouter::delete('finance/expense/{id}', 'ExpenseController@destroy');
        SimpleRouter::put('finance/expense/{id}', 'ExpenseController@update');

        //Revenue
        SimpleRouter::post('finance/revenue', 'RevenueController@store');
        SimpleRouter::get('finance/revenue', 'RevenueController@index');
        SimpleRouter::delete('finance/revenue/{id}', 'RevenueController@destroy');
        SimpleRouter::put('finance/revenue/{id}', 'RevenueController@update');
    });

    //List
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\List', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get('list', 'ListController@index');
        SimpleRouter::get('list/templates', 'ListController@templates');
        SimpleRouter::get('list/{form}/{template}', 'ListController@create');
        SimpleRouter::post('list/{template}', 'ListController@store');
        SimpleRouter::delete('list/{hash}', 'ListController@destroy');
    });
    //List - public routes
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\List'], function () {
        SimpleRouter::get('list/{template}/{hash}', 'ListController@show');
        SimpleRouter::get('list/pdf/{template}/{hash}', 'ListController@export');
    });

    //Quotes
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Quotes', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get('quote/templates', 'QuotesController@templates');
        SimpleRouter::delete('quote/{hash}', 'QuotesController@destroy');
        SimpleRouter::get('quote/create/{form}/{template}', 'QuotesController@create');
        SimpleRouter::post('quote/{template}', 'QuotesController@store');
        SimpleRouter::get('quote', 'QuotesController@index');
    });
    //Quote - public routes
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Quotes'], function () {
        SimpleRouter::get('quote/{template}/{hash}', 'QuotesController@show');
        SimpleRouter::get('quote/pdf/{template}/{hash}', 'QuotesController@export');
    });

    //Grupo de Rotas Recibo
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Recibo', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get('recibo/gerar', 'Recibo@gerar');
        SimpleRouter::get('recibo/gerar/{id_recibo}', 'Recibo@gerar');
        SimpleRouter::get('recibo/criar', 'Recibo@criar');
        SimpleRouter::get('recibo/listar', 'Recibo@listar');
        SimpleRouter::get('recibo/excluir/{id_recibo}', 'Recibo@excluir');
    });

    //Grupo de Rotas Lista
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Config', 'middleware' => [Auth::class, Company::class]], function () {
        SimpleRouter::get('config', 'ConfigControlador@listar');
        SimpleRouter::get('config/excluir/{idUsuario}', 'ConfigControlador@Excluir');
    });

    //Grupo de Rotas Empresa
    SimpleRouter::group(['prefix' => BASE_PATH, 'namespace' => 'Panel\Empresa', 'middleware' => Auth::class], function () {
        SimpleRouter::get('empresa', 'EmpresaControlador@listar');
        SimpleRouter::post('empresa/editar/{id}', 'EmpresaControlador@editar');
        SimpleRouter::match(['get', 'post'], 'empresa/cadastrar', 'EmpresaControlador@cadastrar');
        SimpleRouter::get('empresa/excluir/logo', 'EmpresaControlador@excluirLogo');
    });

    //Grupo de Rotas Dashboard
    SimpleRouter::group([
        'prefix' => BASE_PATH,
        'namespace' => 'Panel\Admin',
        'middleware' => [
            Admin::class,
            Auth::class
        ]
    ], function () {
        SimpleRouter::get('admin/usuarios', 'Admin@usuarios');
        SimpleRouter::get('admin/orcamentos', 'Admin@orcamentos');
        SimpleRouter::get('admin/listas', 'Admin@listas');
        SimpleRouter::get('admin/recibos', 'Admin@recibos');
    });

    //Grupo de Rotas Dashboard
    SimpleRouter::group([
        'prefix' => BASE_PATH,
        'namespace' => 'Panel\Blog',
        'middleware' => [
            Admin::class,
            Auth::class
        ]
    ], function () {
        SimpleRouter::get('admin/categorias', 'BlogControlador@listar');
    });

    SimpleRouter::start();
} catch (Exception $e) {
    if (Helpers::localhost()) {
        echo $e;
    } else {
        Helpers::redirecionar('404');
    }
}
