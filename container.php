<?php

use DI\ContainerBuilder;
use sistema\Servicos\Empresas\EmpresasInterface;
use sistema\Servicos\Empresas\EmpresasServico;
use sistema\Servicos\Financas\FinancasInterface;
use sistema\Servicos\Financas\FinancasServico;
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
    FinancasInterface::class => autowire(FinancasServico::class),
    //Clientes
    sistema\Servicos\Clients\ClientsInterface::class => autowire(sistema\Servicos\Clients\ClientsService::class),
    //Empresa
    EmpresasInterface::class => autowire(EmpresasServico::class),
    //Password Recovery
    sistema\Servicos\PasswordRecovery\PasswordRecoveryInterface::class => autowire(sistema\Servicos\PasswordRecovery\PasswordRecoveryService::class),

    //Adapter
    sistema\Adapter\PdfAdapter\PdfInterface::class => autowire(sistema\Adapter\PdfAdapter\PdfService::class),
    sistema\Adapter\PdfAdapter\DompdfFactoryInterface::class => autowire(sistema\Adapter\PdfAdapter\DompdfFactory::class),

    //Login
    sistema\Servicos\Login\AuthInterface::class => autowire(sistema\Servicos\Login\LoginService::class),
]);

return $builder->build();
