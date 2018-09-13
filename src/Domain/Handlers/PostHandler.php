<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Handlers;

use PDO;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
use SocialNetwork\Application\Commands\PostCommand;
use SocialNetwork\Domain\Aggregates\Wall\WallAggregate;
use SocialNetwork\Infrastructure\Repositories\WallRepository;

class PostHandler
{
    public function __invoke(PostCommand $command)
    {
        $eventStore = new MySqlEventStore(new FQCNMessageFactory(), new PDO('mysql:host=mysql;port=3306;dbname=test;charset=utf8mb4','root','root'), new MySqlAggregateStreamStrategy());
        $userRepository = new WallRepository($eventStore);
        $user = WallAggregate::postNew('John Doe','d');
        $userRepository->save($user);
        $userId = $user->postId();
        echo $userId;
    }
}
