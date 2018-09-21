<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Handlers;

use SocialNetwork\Application\Commands\CommandInterface;
use SocialNetwork\Domain\Aggregates\TimelineAggregate;
use SocialNetwork\Domain\Repository\Persistence\RepositoryInterface;

class FollowHandler
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CommandInterface $command): void
    {
        /**
         * according to GRASP, Creator pattern
         * https://en.wikipedia.org/wiki/GRASP_(object-oriented_design)#Creator
         */
        $timeline = TimelineAggregate::follow(
            $command->getUsername(),
            $command->getFollows()
        );
        $this->repository->save($timeline);
    }
}
