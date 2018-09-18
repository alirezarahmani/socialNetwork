<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Commands;

use Prooph\Common\Messaging\Command;

class PostCommand extends Command implements CommandInterface
{
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
    protected $messageName;

    public function __construct(string $username, string $message)
    {
        $this->username = $username;
        $this->message = $message;
        $this->messageName = __CLASS__;
    }

    public function payload(): array
    {
        $this ->createdAt = date("Y-m-d H:i:s");
        return ['username' => $this->username, 'message' => $this->message, 'createdAt' => $this->createdAt];
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        $this->username = $payload['username'];
        $this->message = $payload['message'];
        $this->createdAt = $payload['createdAt'];
    }
}
