<?php

use DI\ContainerBuilder;
use sistema\Servicos\Empresas\EmpresasInterface;
use sistema\Servicos\Empresas\EmpresasServico;
use sistema\Servicos\Listas\ListaInterface;
use sistema\Servicos\Listas\ListaServicos;
use sistema\Servicos\Orcamentos\OrcamentosInterface;
use sistema\Servicos\Orcamentos\OrcamentosServicos;
use sistema\Servicos\Usuarios\UsuariosInterface;
use sistema\Servicos\Usuarios\UsuariosServico;

use function DI\autowire;

$builder = new ContainerBuilder();

$builder->addDefinitions([

    OrcamentosInterface::class => autowire(OrcamentosServicos::class),
    ListaInterface::class => autowire(ListaServicos::class),
    UsuariosInterface::class => autowire(UsuariosServico::class),

    //Finance
    sistema\Servicos\Finance\CategoryInterface::class => autowire(sistema\Servicos\Finance\CategoryService::class),
    sistema\Servicos\Finance\FinanceInterface::class => autowire(sistema\Servicos\Finance\FinanceService::class),
    sistema\Servicos\Finance\ExpenseInterface::class => autowire(sistema\Servicos\Finance\ExpenseService::class),
    sistema\Servicos\Finance\RevenueInterface::class => autowire(sistema\Servicos\Finance\RevenueService::class),

    sistema\Servicos\Clients\ClientsInterface::class => autowire(sistema\Servicos\Clients\ClientsService::class),
    EmpresasInterface::class => autowire(EmpresasServico::class),
    

    //Adapter
    sistema\Adapter\PdfAdapter\PdfInterface::class => autowire(sistema\Adapter\PdfAdapter\PdfService::class),
    sistema\Adapter\PdfAdapter\DompdfFactoryInterface::class => autowire(sistema\Adapter\PdfAdapter\DompdfFactory::class),

    //Login end Password
    sistema\Servicos\Login\AuthInterface::class => autowire(sistema\Servicos\Login\LoginService::class),
    sistema\Servicos\PasswordRecovery\PasswordRecoveryInterface::class => autowire(sistema\Servicos\PasswordRecovery\PasswordRecoveryService::class),
]);

return $builder->build();
