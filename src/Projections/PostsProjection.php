<?php
declare(strict_types=1);
namespace SocialNetwork\Projections;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use SocialNetwork\Domain\Aggregates\WallAggregate;
use SocialNetwork\Domain\Events\AddPost;
use SocialNetwork\Domain\Repository\NonPersistence\RepositoryInterface;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\WallRepository;

class PostsProjection
{
    /** @var RepositoryInterface  */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute():void
    {
        /** @var MySqlProjectionManager $eventStore */
        $eventStore = SocialNetwork::getContainer()->get(MySqlProjectionManager::class);
        $projection = $eventStore->createProjection('wall_projection');
        $repository = $this->repository;
        $projection->fromCategory(WallAggregate::class)
            ->when(
                [AddPost::class => function (array $state, AddPost $event) use($repository):void {
                        $repository->addByIndex(WallRepository::USERNAME_INDEX, $event->payload());
                }]
            )->run();
    }
}
