<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Projections\FollowProjection;
use SocialNetwork\Projections\PostProjection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

class RunProjectionCli extends SocialNetworkCli
{
    public function __construct(Container $container)
    {
        parent::__construct(null, $container);
    }

    protected function configure(): void
    {
        $this->setName('run:timeline:projection');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $postProjection = $this->container->get(PostProjection::class);
        $postProjection->runAddPost();
        $followProjection = $this->container->get(FollowProjection::class);
        $followProjection->runFollows();
    }
}
