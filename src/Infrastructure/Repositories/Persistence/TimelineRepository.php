<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories\Persistence;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Ramsey\Uuid\Uuid;
use SocialNetwork\Domain\Aggregates\TimelineAggregate;
use SocialNetwork\Domain\Repository\Persistence\RepositoryInterface;

class TimelineRepository extends AggregateRepository implements RepositoryInterface
{
    public function __construct(EventStore $eventStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(TimelineAggregate::class),
            new AggregateTranslator(),
            null, //We don't use a snapshot FOR NOW
            null,
            true
        );
    }

    public function save(AggregateRoot $timeline): void
    {
        $this->saveAggregateRoot($timeline);
    }

    public function get(Uuid $uuid): ?AggregateRoot
    {
        return $this->getAggregateRoot($uuid->toString());
    }
}
