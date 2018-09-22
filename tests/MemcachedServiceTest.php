<?php
namespace Test;

use InvalidArgumentException;
use SocialNetwork\Application\Services\MemcachedService;
use SocialNetwork\Application\Services\TimeService;

class MemcachedServiceTest extends SocialNetwork
{
    /** @var MemcachedService */
    private $memcached;

    public function setUp()
    {
        $this->memcached = new MemcachedService();
    }

    /** @test */
    public function should_set_value_memcached()
    {
        $this->memcached->set('sample-key', 'test');
        $this->assertEquals($this->memcached->get('sample-key'), 'test');
    }
    /** @test */
    public function should_throw_exception_with_long_expire_time()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('TTL too big: '.TimeService::YEAR);
        $this->memcached->setExpire('sample-key', 'test', TimeService::YEAR);
    }
    /** @test */
    public function should_expire_after_one_second()
    {
        $this->memcached->setExpire('exp-sample-key', 'test', TimeService::SECOND);
        sleep(2);
        $this->assertNull($this->memcached->get('exp-sample-key'));
    }
    /** @test */
    public function should_not_expire()
    {
        $this->memcached->setExpire('expire-sample-key', 'testify', TimeService::MINUTE);
        $this->assertEquals($this->memcached->get('expire-sample-key'), 'testify');
    }
}
