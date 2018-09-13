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
    private $uuid;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $message;

    public static function postNew(string $username, string $message): WallAggregate
    {
        /**
         * as we assume we are in sunny day, then there would be no validation
         */
        $uuid = Uuid::uuid4();
        $instance = new self();
        $instance->recordThat(AddPost::occur($uuid->toString(), ['username' => $username, 'message' => $message]));
        return $instance;
    }

    public function postId(): Uuid
    {
        return $this->uuid;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function updateMessage(string $newMessage): void
    {
        if ($newMessage !== $this->message) {
            $this->recordThat(UpdatePost::occur(
                $this->uuid->toString(),
                ['new_message' => $newMessage, 'old_message' => $this->message]
            ));
        }
    }

    /**
     * Every AR needs a hidden method that returns the identifier of the AR as a string
     */
    protected function aggregateId(): string
    {
        return $this->uuid->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (\get_class($event)) {
            case AddPost::class:
                $this->uuid = Uuid::fromString($event->aggregateId());
                $payLoad = $event->post();
                $this->message = $payLoad['message'];
                $this->username = $payLoad['username'];
                break;
            case UpdatePost::class:
                $this->message = $event->newMessage();
                break;
        }
    }
}
