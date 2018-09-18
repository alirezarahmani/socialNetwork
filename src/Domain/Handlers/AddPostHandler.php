<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Handlers;

use SocialNetwork\Application\Commands\CommandInterface;
use SocialNetwork\Domain\Aggregates\WallAggregate;
use SocialNetwork\Domain\Repository\Persistence\RepositoryInterface;

class AddPostHandler
{
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CommandInterface $command): void
    {
        $payload = $command->payload();
        /**
         * according to GRASP, Creator pattern
         * https://en.wikipedia.org/wiki/GRASP_(object-oriented_design)#Creator
         */
        $wall = WallAggregate::addPost(
            $payload['username'],
            $payload['message'],
            $payload['createdAt']
        );
        $this->repository->save($wall);
    }
}
