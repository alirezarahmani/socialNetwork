<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates\Wall;

use Prooph\EventSourcing\AggregateChanged;

class AddPost extends AggregateChanged
{
    public function post(): array
    {
        return [$this->payload['message'], $this->payload['username']];
    }
}
