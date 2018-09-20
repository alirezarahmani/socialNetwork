<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Services\TimeService;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

class ReadCli extends SocialNetworkCli
{
    public function __construct(Container $container)
    {
        parent::__construct(null, $container);
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('read somebody\'s wall');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $username = $input->getFirstArgument();
        $result = $this->container->get(TimelineRepository::class)->findByIndex(TimelineRepository::READ_INDEX, $username);
        if (!empty($result)) {
            $output->success($result, $this->container->get(TimeService::class));
            return;
        }
        $output->failed($username);
    }
}
