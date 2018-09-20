<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Commands;

use Prooph\Common\Messaging\Command;

class FollowCommand extends Command implements CommandInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $follows;

    /**
     * @var string
     */
    protected $messageName;

    public function __construct(string $username, string $follows)
    {
        $this->username = $username;
        $this->follows = $follows;
        $this->messageName = __CLASS__;
    }

    public function payload(): array
    {
        return ['username' => $this->username, 'follows' => $this->follows];
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        $this->username = $payload['username'];
        $this->follows = $payload['follows'];
    }
}
