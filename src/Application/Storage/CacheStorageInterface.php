<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Storage;

interface CacheStorageInterface
{
    public function set(string $key, $value, int $ttl);
    public function get(string $key);
}
