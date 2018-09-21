<?php
declare(strict_types=1);
namespace SocialNetwork\Projections;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\EventStore\Projection\ProjectionManager;
use SocialNetwork\Domain\Aggregates\TimelineAggregate;
use SocialNetwork\Domain\Events\AddPost;
use SocialNetwork\Domain\Events\Follows;
use SocialNetwork\Domain\Repository\NonPersistence\RepositoryInterface;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;

class PostProjection
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

    public function runAddPost():void
    {
        /** @var TimelineRepository $repository */
        $repository = $this->repository;
        $this->projection->fromCategory(TimelineAggregate::class)
            ->when(
                [AddPost::class => function (array $state, AddPost $event) use ($repository):void {
                    $repository->addNewPost($event);
                }]
            )->run();
    }
}
