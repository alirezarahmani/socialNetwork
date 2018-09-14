<?php
declare(strict_types=1);
namespace SocialNetwork\Domain\Aggregates\Wall;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;

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

    public static function addPost(string $username, string $message): WallAggregate
    {
        /**
         * as we assume we are in sunny day, then there would be no validation
         */
        $uuid = Uuid::uuid4();
        $instance = new self();
        $instance->recordThat(
            AddPost::occur(
                $uuid->toString(),
                ['username' => $username, 'message' => $message]
            )
        );
        return $instance;
    }

    public function updatePost(string $newMessage): void
    {
        if ($newMessage !== $this->message) {
            $this->recordThat(UpdatePost::occur(
                $this->id->toString(),
                ['message' => $newMessage]
            ));
        }
    }

    public function getPostId(): Uuid
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getMessage(): string
    {
        return $this->message;
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
