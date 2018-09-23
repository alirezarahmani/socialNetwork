<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use SocialNetwork\Application\Commands\FollowCommand;
use SocialNetwork\Application\Commands\PostCommand;

class FollowCommandTest extends TestCase
{
    public function getFollowCommandTest()
    {
        return new FollowCommand('alireza', 'anton');
    }

    /** @test */
    public function should_work_with_two_argument()
    {
        $command = $this->getFollowCommandTest();
        $this->assertEquals($command->getUsername(), 'alireza');
        $this->assertEquals($command->getFollows(), 'anton');
    }
    /** @test */
    public function should_work_with_two_argument_payload()
    {
        $command = $this->getFollowCommandTest();
        $this->assertEquals($command->payload(), ['username' => 'alireza', 'follows' => 'anton']);
    }
}
