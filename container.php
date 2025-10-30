<?php

use DI\ContainerBuilder;
use app\Servicos\Empresas\EmpresasInterface;
use app\Servicos\Empresas\EmpresasServico;
use app\Servicos\Listas\ListaInterface;
use app\Servicos\Listas\ListaServicos;
use app\Servicos\Orcamentos\OrcamentosInterface;
use app\Servicos\Orcamentos\OrcamentosServicos;
use app\Servicos\Usuarios\UsuariosInterface;
use app\Servicos\Usuarios\UsuariosServico;

use function DI\autowire;

$builder = new ContainerBuilder();

$builder->addDefinitions([

    OrcamentosInterface::class => autowire(OrcamentosServicos::class),
    ListaInterface::class => autowire(ListaServicos::class),
    UsuariosInterface::class => autowire(UsuariosServico::class),

    //Finance
    app\Servicos\Finance\CategoryInterface::class => autowire(app\Servicos\Finance\CategoryService::class),
    app\Servicos\Finance\DashboardInterface::class => autowire(app\Servicos\Finance\DashboardService::class),
    app\Servicos\Finance\ExpenseInterface::class => autowire(app\Servicos\Finance\ExpenseService::class),
    app\Servicos\Finance\RevenueInterface::class => autowire(app\Servicos\Finance\RevenueService::class),

    //Clients
    app\Servicos\Clients\ClientsInterface::class => autowire(app\Servicos\Clients\ClientsService::class),
    EmpresasInterface::class => autowire(EmpresasServico::class),
    
    //Adapter
    app\Adapter\PdfAdapter\PdfInterface::class => autowire(app\Adapter\PdfAdapter\PdfGenerator::class),
    app\Adapter\PdfAdapter\DompdfFactoryInterface::class => autowire(app\Adapter\PdfAdapter\DompdfFactory::class),

    //Login end Password
    app\Servicos\Login\AuthInterface::class => autowire(app\Servicos\Login\LoginService::class),
    app\Servicos\PasswordRecovery\PasswordRecoveryInterface::class => autowire(app\Servicos\PasswordRecovery\PasswordRecoveryService::class),

    //Files
    app\Servicos\Files\FileManagerInterface::class => autowire(app\Servicos\Files\FileManagerService::class),
]);

return $builder->build();
