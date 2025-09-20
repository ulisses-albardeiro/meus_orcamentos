<?php

use Pecee\SimpleRouter\SimpleRouter;
use sistema\Nucleo\AdminMiddleware;
use sistema\Nucleo\AuthMiddleware;
use sistema\Nucleo\Helpers;

try {
    SimpleRouter::setDefaultNamespace('sistema\Controlador');
    SimpleRouter::get(URL, 'SiteControlador@index');
    SimpleRouter::get(URL . '404', 'SiteControlador@erro404');
    SimpleRouter::get(URL . 'politica-de-privacidade', 'SiteControlador@politicaPrivacidade');
    SimpleRouter::get(URL . 'blog', 'BlogControlador@index');

    SimpleRouter::group(['namespace' => 'Login'], function () {

        SimpleRouter::match(['get', 'post'], URL . 'login', 'Login@login');
        SimpleRouter::get(URL . 'sair', 'Login@sair');
    });

    SimpleRouter::group(['namespace' => 'Painel',], function () {

        //Cadastro novos usuários
        SimpleRouter::get(URL . 'form-cadastro', 'CadastroUsuario@form');
        SimpleRouter::post(URL . 'cadastrar-usuario', 'CadastroUsuario@cadastrar');
    });

    //Grupo de rotas de Perfil
    SimpleRouter::group(['namespace' => 'Painel\Perfil', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'perfil', 'PerfilControlador@listar');
        SimpleRouter::post(URL . 'perfil-editar', 'PerfilControlador@editar');
        SimpleRouter::get(URL . 'remover-logo', 'PerfilControlador@removerLogo');
    });

    //Grupo de Rotas Recuperação de senha
    SimpleRouter::group(['namespace' => 'ReculperarSenha'], function () {
        SimpleRouter::match(['get', 'post'], URL . 'recuperacao-de-senha', 'EmaiRecuperacao@emaiRecuperacao');
        SimpleRouter::match(['get', 'post'], URL . 'nova-senha/', 'NovaSenha@novaSenha');
        SimpleRouter::match(['get', 'post'], URL . 'salvar-senha', 'NovaSenha@salvarNovaSenha');
    });

    //Grupo de Rotas Home
    SimpleRouter::group(['namespace' => 'Painel\Home', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'home', 'HomeControlador@listar');
    });

    //Grupo de Rotas Clientes
    SimpleRouter::group(['namespace' => 'Painel\Clientes', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'clientes/listar', 'ClientesControlador@listar');
        SimpleRouter::post(URL . 'clientes/cadastrar', 'ClientesControlador@cadastrar');
        SimpleRouter::get(URL . 'clientes/excluir/{id_cliente}', 'ClientesControlador@excluir');
        SimpleRouter::post(URL . 'clientes/editar/{id_cliente}', 'ClientesControlador@editar');
    });

    //Grupo de Rotas Finanças
    SimpleRouter::group(['namespace' => 'Painel\Financas', 'middleware' => AuthMiddleware::class], function () {
        //dashboard
        SimpleRouter::match(["get", "post"], URL . 'dashboard-financas', 'Dashboard@listar');
        //Nova Categoria
        SimpleRouter::post(URL . 'add-categoria', 'Categoria@cadastrar');
        SimpleRouter::get(URL . 'categoria', 'Categoria@listar');
        SimpleRouter::match(['get', 'post'], URL . 'editar-categoria/{id_categoria}', 'Categoria@editar');
        SimpleRouter::get(URL . 'excluir-categoria/{id_categoria}', 'Categoria@excluir');

        //Despesas
        SimpleRouter::post(URL . 'add-despesa', 'Despesa@cadastrar');
        SimpleRouter::get(URL . 'despesas', 'Despesa@listar');
        SimpleRouter::get(URL . 'excluir-despesa/{id_despesa}', 'Despesa@excluir');
        SimpleRouter::match(['get', 'post'], URL . 'editar-despesa/{id_despesa}', 'Despesa@editar');

        //Receitas
        SimpleRouter::post(URL . 'add-receita', 'Receita@cadastrar');
        SimpleRouter::get(URL . 'receitas', 'Receita@listar');
        SimpleRouter::get(URL . 'excluir-receita/{id_receita}', 'Receita@excluir');
        SimpleRouter::match(['get', 'post'], URL . 'editar-receita/{id_receita}', 'Receita@editar');
    });

    //Grupo de Rotas Lista
    SimpleRouter::group(['namespace' => 'Painel\Lista', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'listas/listar', 'ListaControlador@listar');
        SimpleRouter::get(URL . 'listas/modelos', 'ListaControlador@modelos');
        SimpleRouter::get(URL . 'listas/criar/{form}/{modelo}', 'ListaControlador@criar');
        SimpleRouter::post(URL . 'listas/cadastrar/{modelo}', 'ListaControlador@cadastrar');
        SimpleRouter::get(URL . 'listas/excluir/{hash}', 'ListaControlador@excluir');
    });

    //Grupo de rotas de LIsta que não precisa de autenticação
    SimpleRouter::group(['namespace' => 'Painel\Lista'], function () {
        SimpleRouter::get(URL . 'listas/{modelo}/{hash}', 'ListaControlador@exibir');
        SimpleRouter::get(URL . 'listas/pdf/{modelo}/{hash}', 'ListaControlador@pdf');
    });

    //Grupo de Rotas Orçamento
    SimpleRouter::group(['namespace' => 'Painel\Orcamento', 'middleware' => AuthMiddleware::class], function () {
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
    SimpleRouter::group(['namespace' => 'Painel\Recibo', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'recibo/gerar', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/gerar/{id_recibo}', 'Recibo@gerar');
        SimpleRouter::get(URL . 'recibo/criar', 'Recibo@criar');
        SimpleRouter::get(URL . 'recibo/listar', 'Recibo@listar');
        SimpleRouter::get(URL . 'recibo/excluir/{id_recibo}', 'Recibo@excluir');
    });

    //Grupo de Rotas Lista
    SimpleRouter::group(['namespace' => 'Painel\Config', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'config', 'ConfigControlador@listar');
        SimpleRouter::get(URL . 'config/excluir/{idUsuario}', 'ConfigControlador@Excluir');
    });

    //Grupo de Rotas Empresa
    SimpleRouter::group(['namespace' => 'Painel\Empresa', 'middleware' => AuthMiddleware::class], function () {
        SimpleRouter::get(URL . 'empresa', 'EmpresaControlador@listar');
        SimpleRouter::post(URL . 'empresa/editar/{id}', 'EmpresaControlador@editar');
        SimpleRouter::post(URL . 'empresa/cadastrar', 'EmpresaControlador@cadastrar');
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

    if (!Helpers::localhost()) {
        echo $e;
    } else {
        Helpers::redirecionar('404');
    }
}
