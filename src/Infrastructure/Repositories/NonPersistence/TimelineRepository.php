<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories\NonPersistence;

use SocialNetwork\Application\Storage\CacheIndex;
use SocialNetwork\Application\Storage\CacheStorageInterface;
use SocialNetwork\Domain\Events\AddPost;
use SocialNetwork\Domain\Repository\NonPersistence\RepositoryInterface;

class TimelineRepository extends InMemoryRepository implements RepositoryInterface
{

    private $cacheStorage;

    const READ_INDEX = 'username_index';
    const FOLLOWS_INDEX = 'follows_index';
    const TIMELINE_INDEX = 'time_index';

    public function __construct(CacheStorageInterface $cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
    }

    public static function cacheIndices(): array
    {
        return [
            self::READ_INDEX     => new CacheIndex('username'),
            self::TIMELINE_INDEX => new CacheIndex('username'),
            self::FOLLOWS_INDEX  => new CacheIndex('follows')
        ];
    }

    public function getCacheStorage():CacheStorageInterface
    {
        return $this->cacheStorage;
    }

    public function addNewPost(AddPost $event)
    {
        $payload = $event->payload();
        $this->addByIndex(self::READ_INDEX, $payload);
        $this->addByIndex(self::TIMELINE_INDEX, $payload);
        $followers = $this->getFollowersByUsername($payload['username']);
        // post to followers timeline too.
        foreach ($followers as $follower) {
            $newPayload = $payload;
            $newPayload['username'] = $follower;
            $this->addByIndex(self::TIMELINE_INDEX, $newPayload);
        }
    }

    private function getFollowersByUsername(string $username)
    {
        $result =  $this->findByIndex(self::FOLLOWS_INDEX, $username);
        if (empty($result)) {
            return [];
        }
        return array_column($result, 'username');
    }
}
