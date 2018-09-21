<?php
declare(strict_types=1);
namespace SocialNetwork\Projections;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\EventStore\Projection\ProjectionManager;
use SocialNetwork\Domain\Aggregates\TimelineAggregate;
use SocialNetwork\Domain\Events\AddPost;
use SocialNetwork\Domain\Events\Follows;
use SocialNetwork\Domain\Repository\NonPersistence\RepositoryInterface;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;

class TimelineProjection
{
    private const PROJECTION_NAME = 'post_projection';

    /** @var RepositoryInterface  */
    private $repository;
    /** @var MySqlProjectionManager */
    private $projection;

    public function __construct(RepositoryInterface $repository, ProjectionManager $projectionManager)
    {
        $this->repository = $repository;
        $this->projection = $projectionManager->createProjection(self::PROJECTION_NAME);
    }

    public function run():void
    {
        /** @var TimelineRepository $repository */
        $repository = $this->repository;
        echo 'projection is running ...';
        $this->projection->fromCategory(TimelineAggregate::class)
            ->whenAny(
                function ($state, AggregateChanged $event) use ($repository): void {
                    if (is_a($event, AddPost::class)) {
                        $repository->addNewPost($event);
                    } elseif (is_a($event, Follows::class)) {
                        $repository->addByIndex(TimelineRepository::FOLLOWS_INDEX, $event->payload());
                    }
                    echo '.';
                }
            )->run();
    }
}
