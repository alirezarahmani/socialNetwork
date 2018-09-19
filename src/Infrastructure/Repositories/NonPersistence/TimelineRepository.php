<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories\NonPersistence;

use SocialNetwork\Application\Storage\CacheIndex;
use SocialNetwork\Application\Storage\CacheStorageInterface;
use SocialNetwork\Domain\Repository\NonPersistence\RepositoryInterface;

class TimelineRepository extends InMemoryRepository implements RepositoryInterface
{

    private $cacheStorage;

    const USERNAME_INDEX = 'username_index';
    const FOLLOWS_INDEX = 'follows_index';

    public function __construct(CacheStorageInterface $cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
    }

    public function getCacheStorage():CacheStorageInterface
    {
        return $this->cacheStorage;
    }

    public static function cacheIndices(): array
    {
        return [
            self::USERNAME_INDEX => new CacheIndex('username'),
            self::FOLLOWS_INDEX => new CacheIndex('follows')
        ];
    }
}
