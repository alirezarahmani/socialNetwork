<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Repositories;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Ramsey\Uuid\Uuid;
use SocialNetwork\Domain\Aggregates\Wall\WallAggregate;
use SocialNetwork\Domain\Repository\RepositoryInterface;

class WallRepository extends AggregateRepository implements RepositoryInterface
{
    public function __construct(EventStore $eventStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(WallAggregate::class),
            new AggregateTranslator(),
            null, //We don't use a snapshot FOR NOW
            null, //Also a custom stream name is not required
            true
        );
    }

    public function save(AggregateRoot $wall): void
    {
        $this->saveAggregateRoot($wall);
    }

    public function get(Uuid $uuid): ?AggregateRoot
    {
        return $this->getAggregateRoot($uuid->toString());
    }
}
