<?php
declare(strict_types=1);
namespace Boot;

use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use SocialNetwork\Application\Services\MemcachedService;
use SocialNetwork\Application\Storage\MemcachedCacheStorage;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class SocialNetwork
{
    private static $containerBuilder;

    private function __construct(Container $containerBuilder)
    {
        self::$containerBuilder = $containerBuilder;
    }

    public static function create(): void
    {
        $compiledClassName = 'MyCachedContainer';
        $cacheDir = getenv('VENDOR_DIR') . '/../cache/';
        $cachedContainerFile = "{$cacheDir}container" . '.php';

        if (!is_file($cachedContainerFile)) {
            $container = new ContainerBuilder(new ParameterBag());
            $container->register(MySqlAggregateStreamStrategy::class);
            $container->register(FQCNMessageFactory::class);
            $container->register(\PDO::class, \PDO::class)
                ->addArgument('mysql:host=mysql;port=3306;dbname=my_social_network;charset=utf8mb4')
                ->addArgument('root')
                ->addArgument('root');
            $container->register(MySqlEventStore::class)
                ->addArgument(new Reference(FQCNMessageFactory::class))
                ->addArgument(new Reference(\PDO::class))
                ->addArgument(new Reference(MySqlAggregateStreamStrategy::class))->setPublic(true);
            $container->register(EventDispatcher::class)->setPublic(true);
            $container->register(MemcachedService::class)->setPublic(true);
            $container->register(MemcachedCacheStorage::class)
                ->addArgument(new Reference(MemcachedService::class))
                ->setPublic(true);
            $container->register(MySqlProjectionManager::class)
                ->addArgument(new Reference(MySqlEventStore::class))
                ->addArgument(new Reference(\PDO::class))
                ->addArgument('event_streams')
                ->addArgument('projections')
                ->setPublic(true);
            $container->compile();
            file_put_contents($cachedContainerFile, (new PhpDumper($container))->dump(['class' => $compiledClassName]));
        }

        /** @noinspection PhpIncludeInspection */
        include_once $cachedContainerFile;

        /**
         * @var Container $container
         */
        $container =  new $compiledClassName();
        $request = Request::createFromGlobals();
        $container->set(Request::class, $request);
        new static($container);
        self::addEventSubscribers();
    }

    public static function getContainer(): Container
    {
        return self::$containerBuilder;
    }

    private static function addEventSubscribers(): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = SocialNetwork::getContainer()->get(EventDispatcher::class);
//        $dispatcher->addListener(
//            ApiRegisterDemandEvent::EVENT_NAME,
//            [ApiRegisterDemandListener::class, 'onDemandApiFetch']
//        );
    }
}
