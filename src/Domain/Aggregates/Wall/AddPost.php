<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates\Wall;

use Prooph\EventSourcing\AggregateChanged;

class AddPost extends AggregateChanged implements AggregateInterface
{
    public function payload(): array
    {
        return $this->payload;
    }
}
