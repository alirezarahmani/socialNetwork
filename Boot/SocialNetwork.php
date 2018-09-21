<?php
declare(strict_types=1);
namespace Boot;

use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use SocialNetwork\Application\Commands\FollowCommand;
use SocialNetwork\Application\Commands\PostCommand;
use SocialNetwork\Application\Services\MemcachedService;
use SocialNetwork\Application\Services\TimeService;
use SocialNetwork\Application\Storage\MemcachedCacheStorage;
use SocialNetwork\Domain\Handlers\AddPostHandler;
use SocialNetwork\Domain\Handlers\FollowHandler;
use SocialNetwork\Infrastructure\Cli\AddPostCli;
use SocialNetwork\Infrastructure\Cli\FollowCli;
use SocialNetwork\Infrastructure\Cli\Output\TimelineCliOutput;
use SocialNetwork\Infrastructure\Cli\ReadCli;
use SocialNetwork\Infrastructure\Cli\RunProjectionCli;
use SocialNetwork\Infrastructure\Cli\WallCli;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;
use SocialNetwork\Projections\TimelineProjection;
use SocialNetwork\Infrastructure\Repositories\Persistence\TimelineRepository as PTR;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class SocialNetwork
{
    const PRODUCTION = 'Prod';
    const TEST = 'Test';
    const MODE = [
        self::PRODUCTION,
        self::TEST
    ];

    private static $containerBuilder;

    private function __construct(Container $containerBuilder)
    {
        self::$containerBuilder = $containerBuilder;
    }

    public static function create($mode = self::PRODUCTION): self
    {
        $compiledClassName = 'MyCachedContainer' . $mode;
        $cacheDir = getenv('VENDOR_DIR') . '/../cache/';
        $cachedContainerFile = "{$cacheDir}container" . $mode . '.php';

        if (!is_file($cachedContainerFile)) {
            $container = new ContainerBuilder(new ParameterBag());
            $container->register(MySqlAggregateStreamStrategy::class);
            $container->register(FQCNMessageFactory::class);
            $container->register(TimeService::class)->setPublic(true);
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
            $container->register(TimelineRepository::class)
                ->addArgument(new Reference(MemcachedCacheStorage::class))
                ->setPublic(true);
            $container->register(MySqlProjectionManager::class)
                ->addArgument(new Reference(MySqlEventStore::class))
                ->addArgument(new Reference(\PDO::class))
                ->addArgument('event_streams')
                ->addArgument('projections')
                ->setPublic(true);
            $container->register(TimelineProjection::class)
                ->addArgument(new Reference(TimelineRepository::class))
                ->addArgument(new Reference(MySqlProjectionManager::class))
                ->setPublic(true);
            $container->register(PTR::class)
                ->addArgument(new Reference(MySqlEventStore::class))
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
        self::addEventSubscribers();
        return new static($container);
    }

    public static function getContainer(): Container
    {
        return self::$containerBuilder;
    }

    public static function router(Container $container, CommandBus $commandBus)
    {
        $router = new CommandRouter();
        $router->route(PostCommand::class)->to(new AddPostHandler($container->get(PTR::class)));
        $router->route(FollowCommand::class)->to(new FollowHandler($container->get(PTR::class)));
        $router->attachToMessageBus($commandBus);
    }

    private static function addEventSubscribers(): void
    {
        //@todo : do something here.
    }

    public static function console(Container $container, CommandBus $commandBus)
    {
        $application = new Application();
        $input = new ArgvInput($argv = $_SERVER['argv']);
        $output = new ConsoleOutput();
        array_shift($argv);

        /**
         * no validation here
         */
        switch (count($argv)) {
            case 1:
                if ($argv[0] == 'run:timeline:projection') {
                    $application->add(new RunProjectionCli($container))->run(
                        $input,
                        $output
                    );
                    break;
                }
                $application->add(new ReadCli($container))->run(
                    $input,
                    new TimelineCliOutput()
                );
                break;
            case 2:
                $application->add(new WallCli($container))->run(
                    $input,
                    new TimelineCliOutput()
                );
                break;
            case 3:
                if ($argv[1] == 'follows') {
                    $application->add(new FollowCli($commandBus))->run(
                        $input,
                        $output
                    );
                    break;
                }
                $application->add(new AddPostCli($commandBus))->run(
                    $input,
                    $output
                );
                break;
        }
    }
}
