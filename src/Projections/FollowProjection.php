<?php
declare(strict_types=1);
namespace SocialNetwork\Projections;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use SocialNetwork\Domain\Aggregates\TimelineAggregate;
use SocialNetwork\Domain\Events\Follows;
use SocialNetwork\Domain\Repository\NonPersistence\RepositoryInterface;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;

class FollowProjection
{
    private const PROJECTION_NAME = 'follow_projection';

    /** @var RepositoryInterface  */
    private $repository;
    /** @var MySqlProjectionManager */
    private $projection;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        /** @var MySqlProjectionManager $projectionManager */
        $projectionManager = SocialNetwork::getContainer()->get(MySqlProjectionManager::class);
        $this->projection = $projectionManager->createProjection(self::PROJECTION_NAME);
    }

    public function runFollows():void
    {
        $repository = $this->repository;
        $this->projection->fromCategory(TimelineAggregate::class)
            ->when(
                [Follows::class => function (array $state, Follows $event) use ($repository):void {
                    $repository->addByIndex(TimelineRepository::FOLLOWS_INDEX, $event->payload());
                }]
            )->run();
    }
}
