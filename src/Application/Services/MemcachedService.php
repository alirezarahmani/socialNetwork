<?php
declare(strict_types=1);
namespace SocialNetwork\Application\Services;

use InvalidArgumentException;

class MemcachedService implements ApplicationServiceInterface
{
    /**
     * @var \Memcached
     */
    private $memcached;

    public function get(string $key)
    {
        $value = $this->executeCommand('get', [$key]);
        if (!$value) {
            if ($this->getMemcached()->getResultCode() == \Memcached::RES_NOTFOUND) {
                return null;
            }
            return false;
        }
        return $value;
    }

    public function set(string $key, $value): void
    {
        $this->executeCommand('set', [$key, $value, 0]);
    }

    public function getStats(): array
    {
        $stats = [];
        $hosts = ["memcached:11211", "memcached:11212", "memcached:11213"];
        foreach ($hosts as $host) {
            preg_match('/(?P<protocol>\w+):\/\/(?P<host>[0-9a-z._]*):(?P<port>\d+)/', $host, $matches);
            $memcached = new \Memcached();
            $memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, false);
            $memcached->addServer($matches['host'], $matches['port']);
            $stats = array_merge($stats, $memcached->getStats());
            $memcached = null;
        }
        return $stats;
    }

    public function setExpire(string $key, $value, int $ttl): void
    {
        if ($ttl > TimeService::MONTH) {
            throw new InvalidArgumentException("TTL too big: $ttl");
        }
        $this->executeCommand('set', [$key, $value, $ttl]);
    }

    private function executeCommand(string $command, array $args = [])
    {
        $memcached = $this->getMemcached();
        $value = call_user_func_array([$memcached, $command], $args);
        $resultCode = $memcached->getResultCode();
        if ($resultCode != \Memcached::RES_SUCCESS) {
            if ($resultCode != \Memcached::RES_NOTFOUND && $resultCode != \Memcached::RES_NOTSTORED) {
                throw new \Exception("Invalid response from memcached: " . $memcached->getResultMessage());
            }
        }
        return $value;
    }

    private function getMemcached(): \Memcached
    {
        if ($this->memcached === null) {
            preg_match('/(?P<protocol>\w+):\/\/(?P<host>[0-9a-z._]*):(?P<port>\d+)/', 'memcached:5000', $matches);
            $this->memcached = new \Memcached();
            $this->memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, false);
            $this->memcached->addServer('memcached', 5000);
        }
        return $this->memcached;
    }
}
