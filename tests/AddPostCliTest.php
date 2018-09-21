<?php
namespace Test;

use InvalidArgumentException;
use RuntimeException;
use SocialNetwork\Infrastructure\Cli\AddPostCli;
use Symfony\Component\Console\Tester\CommandTester;

class AddPostCliTest extends SocialNetwork
{
    /** @test */
    public function should_throw_exception_with_less_argument()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "sign").');
        $command = new CommandTester(new AddPostCli($this->commandBus));
        $command->execute(['message' => 'hi there']);
    }

    /** @test */
    public function should_throw_exception_with_more_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "more-argument" argument does not exist.');
        $command = new CommandTester(new AddPostCli($this->commandBus));
        $command->execute(['message' => 'hi there', 'sign' => 'hi there ' , 'more-argument' => 'this is more']);
    }
}
