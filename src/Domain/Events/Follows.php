<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Events;

use Prooph\EventSourcing\AggregateChanged;

class Follows extends AggregateChanged
{
    public function payload(): array
    {
        return $this->payload;
    }
}
