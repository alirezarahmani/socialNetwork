<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Storage;

class CacheIndex
{
    private const KEY = 'Storage:';
    private $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getKey(string $index, string $value)
    {
        return self::KEY . ':' . $index . ':' . static::class . ':' . $this->field . ':' . $value;
    }
}
