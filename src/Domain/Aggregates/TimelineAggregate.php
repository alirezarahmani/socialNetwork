<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;
use SocialNetwork\Domain\Events\AddPost;
use SocialNetwork\Domain\Events\Follows;

class TimelineAggregate extends AggregateRoot
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
    private $follows;

    /**
     * @var string
     */
    private $createdAt;

    public static function follow(string $username, string $follows): TimelineAggregate
    {
        /**
         * as we assume we are in sunny day, then there would be no validation
         */
        $instance = new self();
        $instance->recordThat(
            Follows::occur(
                (Uuid::uuid4())->toString(),
                ['username' => $username, 'follows' => $follows, 'createAt' => date("Y-m-d H:i:s")]
            )
        );
        return $instance;
    }

    public static function addPost(string $username, string $message): TimelineAggregate
    {
        /**
         * as we assume we are in sunny day, then there would be no validation
         */
        $instance = new self();
        $instance->recordThat(
            AddPost::occur(
                (Uuid::uuid4())->toString(),
                ['username' => $username, 'message' => $message, 'createAt' => date("Y-m-d H:i:s")]
            )
        );
        return $instance;
    }

    public function getId()
    {
        return $this->id;
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
