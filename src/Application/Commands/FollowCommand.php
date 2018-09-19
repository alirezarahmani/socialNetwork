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
    private $follow;

    /**
     * @var string
     */
    protected $messageName;

    public function __construct(string $username, string $follow)
    {
        $this->username = $username;
        $this->follow = $follow;
        $this->messageName = __CLASS__;
    }

    public function payload(): array
    {
        return ['username' => $this->username, 'follows' => $this->follow];
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        $this->username = $payload['username'];
        $this->follow = $payload['follow'];
    }
}
