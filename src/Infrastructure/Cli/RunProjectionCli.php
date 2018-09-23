<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Projections\TimelineProjection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunProjectionCli extends SocialNetwork
{
    protected function configure(): void
    {
        $this->setName('run:timeline:projection');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->container->get(TimelineProjection::class)->run();
    }
}
