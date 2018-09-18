<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Repository\NonPersistence;

use SocialNetwork\Application\Storage\CacheStorageInterface;

interface RepositoryInterface
{
    public function __construct(CacheStorageInterface $cacheStorage);
    public static function cacheIndices(): array;
    public function getCacheStorage(): CacheStorageInterface;
}
