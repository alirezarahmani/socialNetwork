<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates\Wall;

interface AggregateInterface
{
    public function payload():array;
}
