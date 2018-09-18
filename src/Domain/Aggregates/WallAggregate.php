<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;
use SocialNetwork\Domain\Events\AddPost;

class WallAggregate extends AggregateRoot
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $createdAt;

    public static function addPost(string $username, string $message, string $createdAt): WallAggregate
    {
        /**
         * as we assume we are in sunny day, then there would be no validation
         */
        $instance = new self();
        $instance->recordThat(
            AddPost::occur(
                (Uuid::uuid4())->toString(),
                ['username' => $username, 'message' => $message, 'create_at' => $createdAt]
            )
        );
        return $instance;
    }

    /**
     * Every AggregateRoot needs a hidden method that returns the identifier of the AggregateRoot as a string
     */
    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        $this->id = Uuid::fromString($event->aggregateId());
        foreach ($event->payload() as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
