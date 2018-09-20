<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories\NonPersistence;

use Assert\Assertion;
use SocialNetwork\Application\Services\TimeService;
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

    public function getCacheStorage(): CacheStorageInterface
    {
        return $this->cacheStorage;
    }

    public function addNewPost(AddPost $event): void
    {
        $payload = $event->payload();
        $this->addByIndex(self::READ_INDEX, $payload);
        $this->addByIndex(self::TIMELINE_INDEX, $payload);
        $followers = $this->getFollowersByUsername($payload['username']);
        // post to followers timeline too.
        foreach ($followers as $follower) {
            $this->addToFollowerWallByIndex(self::TIMELINE_INDEX, $follower, $payload);
        }
    }

    public function addToFollowerWallByIndex(string $index, string $follower, array $values): void
    {
        Assertion::keyExists($indices = static::cacheIndices(), $index, 'wrong cache indices index, the index: ' . $index . ' not exist!');
        $indict = $indices[$index];
        Assertion::keyExists($values, $indict->getField(), 'wrong values to insert, unable to find :' . $indict->getField());
        $result[] = $values;
        if ($data = static::getCacheStorage()->get($indict->getKey($index, $follower))) {
            $data[] = $values;
            $result = $data;
        }
        static::getCacheStorage()->set($indict->getKey($index, $follower), $result, TimeService::MONTH);
    }

    private function getFollowersByUsername(string $username): array
    {
        $result =  $this->findByIndex(self::FOLLOWS_INDEX, $username);
        if (empty($result)) {
            return [];
        }
        // as we assume we are in sunny day
        return array_unique(array_column($result, 'username'));
    }
}
