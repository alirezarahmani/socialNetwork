<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Boot\SocialNetwork;
use SocialNetwork\Application\Storage\MemcachedCacheStorage;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;
use SocialNetwork\Projections\TimelineProjection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunProjectionCli extends SocialNetworkCli
{
    protected function configure(): void
    {
        $this->setName('run:timeline:projection');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var MemcachedCacheStorage $cacheStorage */
        $cacheStorage = SocialNetwork::getContainer()->get(MemcachedCacheStorage::class);
        $timelineProjection = new TimelineProjection(new TimelineRepository($cacheStorage));
        $timelineProjection->runAddPost();
        $timelineProjection->runFollows();
    }
}
