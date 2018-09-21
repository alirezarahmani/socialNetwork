<?php
namespace Test;

class TimelineCliOutputTest extends SocialNetwork
{
    /** @test */
    public function should_throw_exception_with_empty_result()
    {
        $this->expectException(\Assert\AssertionFailedException::class);
        $this->expectExceptionMessage('sorry, result is not valid');
        $this->timelineCli->success([], $this->timeService);
    }
}
