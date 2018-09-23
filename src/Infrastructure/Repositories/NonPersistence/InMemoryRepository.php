<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories\NonPersistence;

use Assert\Assertion;
use SocialNetwork\Application\Services\TimeService;

abstract class InMemoryRepository
{
    public function findByIndex(string $index, $value): ?array
    {
        $indices = static::cacheIndices();
        Assertion::keyExists($indices, $index, 'wrong cache index, the index: ' . $index . ' not exist!');
        return static::getCacheStorage()->get($indices[$index]->getKey($index, $value));
    }

    public function addByIndex(string $index, array $values): void
    {
        Assertion::keyExists($indices = static::cacheIndices(), $index, 'wrong cache index, the index: ' . $index . ' not exist!');
        $indices = $indices[$index];
        Assertion::keyExists($values, $indices->getField(), 'wrong values to insert, unable to find field:' . $indices->getField());
        $result[] = $values;
        if ($data = static::getCacheStorage()->get($indices->getKey($index, $values[$indices->getField()]))) {
            // append new values
            $data[] = $values;
            $result = $data;
        }
        static::getCacheStorage()->set($indices->getKey($index, $values[$indices->getField()]), $result, 0);
    }
}
