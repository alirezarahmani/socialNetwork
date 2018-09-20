<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Storage;

use SocialNetwork\Application\Services\MemcachedService;

class MemcachedCacheStorage implements CacheStorageInterface
{
    private $memcached;

    public function __construct(MemcachedService $memcached)
    {
        $this->memcached = $memcached;
    }

    public function set(string $key, $value, int $ttl)
    {
        $this->memcached->setExpire($key, $value, $ttl);
    }

    public function get(string $key)
    {
        return $this->memcached->get($key);
    }
}
