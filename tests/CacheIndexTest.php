<?php
namespace Test;


use SocialNetwork\Application\Storage\CacheIndex;

class CacheIndexTest extends SocialNetwork
{
    /** @test */
    public function should_work_with_set_field_argument()
    {
        $cacheIndex = new CacheIndex('test');
        $this->assertEquals($cacheIndex->getField(), 'test');
    }

    /** @test */
    public function should_work_with_generate_key()
    {
        $index = 'test-index';
        $cacheIndex = new CacheIndex('test');
        $key = $cacheIndex->getKey($index, 'hi-test');
        $this->assertEquals($key, CacheIndex::KEY_PREFIX . ':' . $index . ':' . 'test' . ':' . 'hi-test');
    }
}
