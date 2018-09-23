<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use SocialNetwork\Application\Commands\PostCommand;

class PostCommandTest extends TestCase
{
    public function getPostCommandTest()
    {
        return new PostCommand('test', 'test-test');
    }

    /** @test */
    public function should_work_with_two_argument()
    {
        $command = $this->getPostCommandTest();
        $this->assertEquals($command->getUsername(), 'test');
        $this->assertEquals($command->getMessage(), 'test-test');
    }
    /** @test */
    public function should_work_with_two_argument_payload()
    {
        $command = $this->getPostCommandTest();
        $this->assertEquals($command->payload(), ['username' => 'test', 'message' => 'test-test']);
    }
}
