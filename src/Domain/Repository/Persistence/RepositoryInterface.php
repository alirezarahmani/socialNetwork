<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Repository\Persistence;

use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;

interface RepositoryInterface
{
    public function save(AggregateRoot $aggregate): void;
    public function get(Uuid $uuid): ?AggregateRoot;
}
