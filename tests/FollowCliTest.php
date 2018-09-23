<?php
namespace Test;

use InvalidArgumentException;
use RuntimeException;
use SocialNetwork\Infrastructure\Cli\AddPostCli;
use SocialNetwork\Infrastructure\Cli\FollowCli;
use Symfony\Component\Console\Tester\CommandTester;

class FollowCliTest extends SocialNetwork
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        \Mockery::close();
    }

    /** @test */
    public function should_throw_exception_with_less_argument()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "follows, other_username").');
        $command = new CommandTester(new FollowCli($this->commandBus, $this->container));
        $command->execute(['username' => 'hi there']);
    }

    /** @test */
    public function should_throw_exception_with_more_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "message" argument does not exist.');
        $command = new CommandTester(new FollowCli($this->commandBus, $this->container));
        $command->execute(['message' => 'hi there', 'follows' => 'hi there ' , 'more-argument' => 'this is more']);
    }

    /** @test */
    public function should_display_well_done_in_console()
    {
        $this->commandBus->shouldReceive('dispatch')->times(1)->andReturnNull();
        $command = new CommandTester(new FollowCli($this->commandBus, $this->container));
        $command->execute(['username' => 'alireza', 'follows' => 'hi there', 'other_username' => 'anton']);
        $this->assertEquals($command->getDisplay(),'alireza now, is following anton 
' );
    }
}