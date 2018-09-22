<?php
namespace Test;

use SocialNetwork\Domain\Aggregates\TimelineAggregate;

class TimelineAggregateTest extends SocialNetwork
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
    public function add_post_should_work_with_arguments()
    {
        $aggregate =  TimelineAggregate::addPost('test-username', 'test-message');
        $this->assertInstanceOf(TimelineAggregate::class, $aggregate);
    }

    /** @test */
    public function add_follower_should_work_with_arguments()
    {
        $aggregate =  TimelineAggregate::follow('test-username', 'test-follower');
        $this->assertInstanceOf(TimelineAggregate::class, $aggregate);
    }
}
