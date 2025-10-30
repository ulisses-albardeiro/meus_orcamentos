<?php

use DI\ContainerBuilder;
use App\Servicos\Empresas\EmpresasInterface;
use App\Servicos\Empresas\EmpresasServico;
use App\Servicos\Listas\ListaInterface;
use App\Servicos\Listas\ListaServicos;
use App\Servicos\Orcamentos\OrcamentosInterface;
use App\Servicos\Orcamentos\OrcamentosServicos;
use App\Servicos\Usuarios\UsuariosInterface;
use App\Servicos\Usuarios\UsuariosServico;

use function DI\autowire;

$builder = new ContainerBuilder();

$builder->addDefinitions([

    OrcamentosInterface::class => autowire(OrcamentosServicos::class),
    ListaInterface::class => autowire(ListaServicos::class),
    UsuariosInterface::class => autowire(UsuariosServico::class),

    //Finance
    App\Servicos\Finance\CategoryInterface::class => autowire(App\Servicos\Finance\CategoryService::class),
    App\Servicos\Finance\DashboardInterface::class => autowire(App\Servicos\Finance\DashboardService::class),
    App\Servicos\Finance\ExpenseInterface::class => autowire(App\Servicos\Finance\ExpenseService::class),
    App\Servicos\Finance\RevenueInterface::class => autowire(App\Servicos\Finance\RevenueService::class),

    //Clients
    App\Servicos\Clients\ClientsInterface::class => autowire(App\Servicos\Clients\ClientsService::class),
    EmpresasInterface::class => autowire(EmpresasServico::class),
    
    //Adapter
    App\Adapter\PdfAdapter\PdfInterface::class => autowire(App\Adapter\PdfAdapter\PdfGenerator::class),
    App\Adapter\PdfAdapter\DompdfFactoryInterface::class => autowire(App\Adapter\PdfAdapter\DompdfFactory::class),

    //Login end Password
    App\Servicos\Login\AuthInterface::class => autowire(App\Servicos\Login\LoginService::class),
    App\Servicos\PasswordRecovery\PasswordRecoveryInterface::class => autowire(App\Servicos\PasswordRecovery\PasswordRecoveryService::class),

    //Files
    App\Servicos\Files\FileManagerInterface::class => autowire(App\Servicos\Files\FileManagerService::class),
]);

return $builder->build();
