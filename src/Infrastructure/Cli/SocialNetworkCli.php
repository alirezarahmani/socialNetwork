<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Symfony\Component\Console\Command\Command;

abstract class SocialNetworkCli extends Command
{
    protected $commandBus;
    protected $router;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->addOption('force');
        $this->commandBus = new CommandBus();
        $this->router = new CommandRouter();
    }
}
