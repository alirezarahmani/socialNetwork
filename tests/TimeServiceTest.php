<?php
namespace Test;

use SocialNetwork\Application\Services\TimeService;

class TimeServiceTest extends SocialNetwork
{
    /** @var TimeService */
    protected $timeService;

    public function setUp()
    {
        $this->timeService = new TimeService();
    }
    /** @test */
    public function should_return_min_two_ago()
    {
        $twoMinsAgo = strtotime('-2 minutes');
        $this->assertEquals($this->timeService->elapsed($twoMinsAgo), '(2 minutes ago)');
    }

    /** @test */
    public function should_return_week_one_ago()
    {
        $twoMinsAgo = strtotime('-1 week');
        $this->assertEquals($this->timeService->elapsed($twoMinsAgo), '(1 week ago)');
    }

    /** @test */
    public function should_return_month_one_ago()
    {
        $twoMinsAgo = strtotime('-1 month');
        $this->assertEquals($this->timeService->elapsed($twoMinsAgo), '(1 month ago)');
    }
}
