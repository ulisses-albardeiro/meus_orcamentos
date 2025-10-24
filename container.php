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

    //Service
    sistema\Servicos\Finance\CategoryInterface::class => autowire(sistema\Servicos\Finance\CategoryService::class),
    sistema\Servicos\Finance\FinanceInterface::class => autowire(sistema\Servicos\Finance\FinanceService::class),

    sistema\Servicos\Clients\ClientsInterface::class => autowire(sistema\Servicos\Clients\ClientsService::class),
    EmpresasInterface::class => autowire(EmpresasServico::class),
    
    sistema\Servicos\PasswordRecovery\PasswordRecoveryInterface::class => autowire(sistema\Servicos\PasswordRecovery\PasswordRecoveryService::class),

    //Adapter
    sistema\Adapter\PdfAdapter\PdfInterface::class => autowire(sistema\Adapter\PdfAdapter\PdfService::class),
    sistema\Adapter\PdfAdapter\DompdfFactoryInterface::class => autowire(sistema\Adapter\PdfAdapter\DompdfFactory::class),
    sistema\Servicos\Login\AuthInterface::class => autowire(sistema\Servicos\Login\LoginService::class),
]);

return $builder->build();
