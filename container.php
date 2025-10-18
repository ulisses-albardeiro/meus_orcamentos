<?php

use DI\ContainerBuilder;
use sistema\Servicos\Clientes\ClientesInterface;
use sistema\Servicos\Clientes\ClientesServico;
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
    ClientesInterface::class => autowire(ClientesServico::class),
    //Empresa
    EmpresasInterface::class => autowire(EmpresasServico::class),
    //Password Recovery
    sistema\Servicos\PasswordRecovery\PasswordRecoveryInterface::class => autowire(sistema\Servicos\PasswordRecovery\PasswordRecoveryService::class),
]);

return $builder->build();
