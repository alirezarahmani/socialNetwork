<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Boot\SocialNetwork;
use SocialNetwork\Application\Storage\MemcachedCacheStorage;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\WallRepository;
use SocialNetwork\Projections\PostsProjection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunProjectionCli extends SocialNetworkCli
{
    protected function configure(): void
    {
        $this
            ->setName('run:projection');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var MemcachedCacheStorage $cacheStorage */
        $cacheStorage = SocialNetwork::getContainer()->get(MemcachedCacheStorage::class);
        (new PostsProjection(new WallRepository($cacheStorage)))->execute();
    }
}
