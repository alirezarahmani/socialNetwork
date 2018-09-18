<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class MyCachedContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    /**
     * @internal but protected for BC on cache:clear
     */
    protected $privates = array();

    public function __construct()
    {
        $this->services = $this->privates = array();
        $this->methodMap = array(
            'Prooph\\EventStore\\Pdo\\MySqlEventStore' => 'getMySqlEventStoreService',
            'Prooph\\EventStore\\Pdo\\Projection\\MySqlProjectionManager' => 'getMySqlProjectionManagerService',
            'SocialNetwork\\Application\\Services\\MemcachedService' => 'getMemcachedServiceService',
            'SocialNetwork\\Application\\Storage\\MemcachedCacheStorage' => 'getMemcachedCacheStorageService',
            'Symfony\\Component\\EventDispatcher\\EventDispatcher' => 'getEventDispatcherService',
        );

        $this->aliases = array();
    }

    public function reset()
    {
        $this->privates = array();
        parent::reset();
    }

    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    public function isCompiled()
    {
        return true;
    }

    public function getRemovedIds()
    {
        return array(
            'PDO' => true,
            'Prooph\\Common\\Messaging\\FQCNMessageFactory' => true,
            'Prooph\\EventStore\\Pdo\\PersistenceStrategy\\MySqlAggregateStreamStrategy' => true,
            'Psr\\Container\\ContainerInterface' => true,
            'Symfony\\Component\\DependencyInjection\\ContainerInterface' => true,
        );
    }

    /**
     * Gets the public 'Prooph\EventStore\Pdo\MySqlEventStore' shared service.
     *
     * @return \Prooph\EventStore\Pdo\MySqlEventStore
     */
    protected function getMySqlEventStoreService()
    {
        return $this->services['Prooph\EventStore\Pdo\MySqlEventStore'] = new \Prooph\EventStore\Pdo\MySqlEventStore(new \Prooph\Common\Messaging\FQCNMessageFactory(), ($this->privates['PDO'] ?? $this->privates['PDO'] = new \PDO('mysql:host=mysql;port=3306;dbname=my_social_network;charset=utf8mb4', 'root', 'root')), new \Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy());
    }

    /**
     * Gets the public 'Prooph\EventStore\Pdo\Projection\MySqlProjectionManager' shared service.
     *
     * @return \Prooph\EventStore\Pdo\Projection\MySqlProjectionManager
     */
    protected function getMySqlProjectionManagerService()
    {
        return $this->services['Prooph\EventStore\Pdo\Projection\MySqlProjectionManager'] = new \Prooph\EventStore\Pdo\Projection\MySqlProjectionManager(($this->services['Prooph\EventStore\Pdo\MySqlEventStore'] ?? $this->getMySqlEventStoreService()), ($this->privates['PDO'] ?? $this->privates['PDO'] = new \PDO('mysql:host=mysql;port=3306;dbname=my_social_network;charset=utf8mb4', 'root', 'root')), 'event_streams', 'projections');
    }

    /**
     * Gets the public 'SocialNetwork\Application\Services\MemcachedService' shared service.
     *
     * @return \SocialNetwork\Application\Services\MemcachedService
     */
    protected function getMemcachedServiceService()
    {
        return $this->services['SocialNetwork\Application\Services\MemcachedService'] = new \SocialNetwork\Application\Services\MemcachedService();
    }

    /**
     * Gets the public 'SocialNetwork\Application\Storage\MemcachedCacheStorage' shared service.
     *
     * @return \SocialNetwork\Application\Storage\MemcachedCacheStorage
     */
    protected function getMemcachedCacheStorageService()
    {
        return $this->services['SocialNetwork\Application\Storage\MemcachedCacheStorage'] = new \SocialNetwork\Application\Storage\MemcachedCacheStorage(($this->services['SocialNetwork\Application\Services\MemcachedService'] ?? $this->services['SocialNetwork\Application\Services\MemcachedService'] = new \SocialNetwork\Application\Services\MemcachedService()));
    }

    /**
     * Gets the public 'Symfony\Component\EventDispatcher\EventDispatcher' shared service.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcherService()
    {
        return $this->services['Symfony\Component\EventDispatcher\EventDispatcher'] = new \Symfony\Component\EventDispatcher\EventDispatcher();
    }
}
