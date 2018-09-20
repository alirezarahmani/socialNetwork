<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories\NonPersistence;

use Assert\Assertion;
use SocialNetwork\Application\Services\TimeService;

abstract class InMemoryRepository
{
    public function findByIndex(string $index, $value):?array
    {
        $indices = static::cacheIndices();
        Assertion::keyExists($indices, $index, 'wrong cache indices index, the index: ' . $index . ' not exist!');
        return static::getCacheStorage()->get($indices[$index]->getKey($index, $value));
    }

    public function addByIndex(string $index, array $values): void
    {
        Assertion::keyExists($indices = static::cacheIndices(), $index, 'wrong cache indices index, the index: ' . $index . ' not exist!');
        $indict = $indices[$index];
        $result[] = $values;
        if ($data = static::getCacheStorage()->get($indict->getKey($index, $values[$indict->getField()]))) {
            $data[] = $values;
            $result = $data;
        }
        Assertion::keyExists($values, $indict->getField(), 'wrong values to insert, unable to find :' . $indict->getField());
        static::getCacheStorage()->set($indict->getKey($index, $values[$indict->getField()]), $result, TimeService::MONTH);
    }
}
