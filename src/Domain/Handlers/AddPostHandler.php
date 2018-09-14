<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Handlers;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\MySqlEventStore;
use SocialNetwork\Application\Commands\PostCommand;
use SocialNetwork\Domain\Aggregates\Wall\WallAggregate;
use SocialNetwork\Infrastructure\Repositories\WallRepository;

class AddPostHandler
{
    public function __invoke(PostCommand $command)
    {
        $payload = $command->payload();
        /** @var MySqlEventStore $eventStore */
        $eventStore = SocialNetwork::getContainer()->get(MySqlEventStore::class);
        $userRepository = new WallRepository($eventStore);
        $user = WallAggregate::addPost($payload['username'], $payload['message']);
        $userRepository->save($user);
        $userId = $user->getPostId();
        echo $userId;
    }
}
