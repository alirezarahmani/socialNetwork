<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Boot\SocialNetwork;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;

abstract class SocialNetworkCli extends Command
{
    protected $commandBus;
    protected $container;

    public function __construct(?CommandBus $commandBus, Container $container = null)
    {
        parent::__construct(null);
        $this->addOption('force');
        $this->commandBus = $commandBus;
        $this->container = $container;
    }
}
