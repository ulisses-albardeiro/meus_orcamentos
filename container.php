<?php

use DI\ContainerBuilder;

use App\Services\Orcamentos\OrcamentosInterface;
use App\Services\Orcamentos\OrcamentosServicos;
use App\Services\User\UserInterface;
use App\Services\User\UserService;

use function DI\autowire;

$builder = new ContainerBuilder();

$builder->addDefinitions([

    OrcamentosInterface::class => autowire(OrcamentosServicos::class),
    App\Services\Lists\ListInterface::class => autowire(App\Services\Lists\ListService::class),
    UserInterface::class => autowire(UserService::class),

    //Finance
    App\Services\Finance\CategoryInterface::class => autowire(App\Services\Finance\CategoryService::class),
    App\Services\Finance\DashboardInterface::class => autowire(App\Services\Finance\DashboardService::class),
    App\Services\Finance\ExpenseInterface::class => autowire(App\Services\Finance\ExpenseService::class),
    App\Services\Finance\RevenueInterface::class => autowire(App\Services\Finance\RevenueService::class),

    //Clients
    App\Services\Clients\ClientsInterface::class => autowire(App\Services\Clients\ClientsService::class),
    App\Services\Company\CompanyInterface::class => autowire(App\Services\Company\CompanyService::class),
    
    //Adapter
    App\Adapters\PdfAdapter\PdfInterface::class => autowire(App\Adapters\PdfAdapter\PdfGenerator::class),
    App\Adapters\PdfAdapter\DompdfFactoryInterface::class => autowire(App\Adapters\PdfAdapter\DompdfFactory::class),
    
    App\Adapters\MailAdapter\MailInterface::class => autowire(App\Adapters\MailAdapter\EmailSender::class),
    App\Adapters\MailAdapter\PhpMailFactoryInterface::class => autowire(App\Adapters\MailAdapter\PhpMailFactory::class),

    //Login end Password
    App\Services\Login\AuthInterface::class => autowire(App\Services\Login\LoginService::class),
    App\Services\PasswordRecovery\PasswordRecoveryInterface::class => autowire(App\Services\PasswordRecovery\PasswordRecoveryService::class),

    //Files
    App\Services\Files\FileManagerInterface::class => autowire(App\Services\Files\FileManagerService::class),
]);

return $builder->build();
