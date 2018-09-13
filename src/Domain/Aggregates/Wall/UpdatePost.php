<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates\Wall;

use Prooph\EventSourcing\AggregateChanged;

class UpdatePost extends AggregateChanged
{
    public function newMessage(): string
    {
        return $this->payload['new_message'];
    }

    public function oldMessage(): string
    {
        return $this->payload['old_message'];
    }
}
