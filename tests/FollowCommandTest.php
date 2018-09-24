<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use SocialNetwork\Application\Commands\FollowCommand;
use SocialNetwork\Application\Commands\PostCommand;

class FollowCommandTest extends TestCase
{
    public function getFollowCommandTest()
    {
        return new FollowCommand('testguy', 'test');
    }

    /** @test */
    public function should_work_with_two_argument()
    {
        $command = $this->getFollowCommandTest();
        $this->assertEquals($command->getUsername(), 'testguy');
        $this->assertEquals($command->getFollows(), 'test');
    }
    /** @test */
    public function should_work_with_two_argument_payload()
    {
        $command = $this->getFollowCommandTest();
        $this->assertEquals($command->payload(), ['username' => 'testguy', 'follows' => 'test']);
    }
}
