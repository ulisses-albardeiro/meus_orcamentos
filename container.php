<?php

use DI\ContainerBuilder;
use sistema\Modelos\ClientesModelo;
use sistema\Servicos\Clientes\ClientesInterface;
use sistema\Servicos\Clientes\ClientesServico;
use sistema\Servicos\Orcamentos\OrcamentosInterface;
use sistema\Servicos\Orcamentos\OrcamentosServicos;

use function DI\autowire;

$builder = new ContainerBuilder();

$builder->addDefinitions([

    OrcamentosInterface::class => autowire(OrcamentosServicos::class),

    //Clientes
    ClientesInterface::class => autowire(ClientesServico::class),
]);

return $builder->build();
