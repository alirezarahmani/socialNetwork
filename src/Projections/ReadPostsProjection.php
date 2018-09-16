<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Projections;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use SocialNetwork\Domain\Aggregates\AddPost;
use SocialNetwork\Domain\Aggregates\WallAggregate;

class ReadPostsProjection
{
    public function byUsername()
    {
        /** @var MySqlProjectionManager $eventStore */
        $eventStore = SocialNetwork::getContainer()->get(MySqlProjectionManager::class);
        $msg = [];
        $val = $eventStore
            ->createQuery();

        $val->fromCategory(WallAggregate::class)
            ->when(
                [
                    AddPost::class => function (array $state, AddPost $event) use (&$msg):void {
                        if ($event->payload()['username'] == 'ww') {
                            $msg[] = $event->payload()['message'];
                        }
                    }
                ]
            )->run();

        var_dump($msg);
        exit;
    }
}
