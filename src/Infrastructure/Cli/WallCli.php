<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Services\TimeService;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

class WallCli extends SocialNetwork
{
    protected function configure()
    {
        $this
            ->setName('wall')
            ->addArgument('username', InputArgument::REQUIRED)
            ->setDescription('read somebody\'s wall');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $username = $input->getArgument('username');
        $result = $this->container->get(TimelineRepository::class)->findByIndex(
            TimelineRepository::TIMELINE_INDEX,
            $username
        );
        $output->asList($result, $this->container->get(TimeService::class));
    }
}
