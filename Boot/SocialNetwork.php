<?php
declare(strict_types=1);
namespace Boot;

use ArrayObject;
use Assert\Assertion;
use Exception;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use SocialNetwork\Application\Services\MemcachedService;
use SocialNetwork\Application\Services\TimeService;
use SocialNetwork\Application\Storage\MemcachedCacheStorage;
use SocialNetwork\Infrastructure\Cli\Output\ConsoleInput;
use SocialNetwork\Infrastructure\Cli\Output\ConsoleOutput;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;
use SocialNetwork\Projections\TimelineProjection;
use SocialNetwork\Infrastructure\Repositories\Persistence\TimelineRepository as PTR;
use SplFileInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class SocialNetwork
{
    const CONSOLE_APPLICATION = 'console_application';
    const PRODUCTION = 'Prod';
    const TEST = 'Test';
    const MODES = [
        self::PRODUCTION,
        self::TEST
    ];
    private static $containerBuilder;

    private function __construct(Container $containerBuilder)
    {
        self::$containerBuilder = $containerBuilder;
    }

    public function explode(): void
    {
        if (PHP_SAPI == "cli") {
            $this->runCli(new ConsoleInput(), new ConsoleOutput());
        } else {
            $this->runHttp();
        }
    }

    private function runHttp()
    {
        /**
         * if we have Http
         */
    }

    private function runCli(InputInterface $input, OutputInterface $output): void
    {
        $application = new Application();
        $application->setAutoExit(false);
        /** @var Container $container */
        $container = self::$containerBuilder;
        $commandBus = $container->get(CommandBus::class);
        foreach ($container->get(self::CONSOLE_APPLICATION)['classes'] as $class) {
            $application->add(new $class($commandBus, $container));
        }
        $application->setCatchExceptions(false);
        try {
            $application->run($input, $output);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private static function loadConsoleApplications(string $appPath): array
    {
        $classes = [];
        $dir = $appPath . '/src/Infrastructure/Cli';
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('no valid directory');
        }
        $finder = new Finder();

        foreach ($finder->files()->name('*Cli.php')->in($dir) as $file) {
            /**
             * @var SplFileInfo $file
             */
            $className = 'SocialNetwork\\Infrastructure\\Cli\\'.substr($file->getRelativePathname(), 0, -4);
            $reflection = new \ReflectionClass($className);
            if ($reflection->isInstantiable()) {
                $classes[] = $className;
            }
        }
        return ['classes' => $classes];
    }

    public static function create($mode = self::PRODUCTION): self
    {
        $compiledClassName = 'MyCachedContainer' . $mode;
        $cacheDir = __DIR__ . '/../cache/';
        $cachedContainerFile = "{$cacheDir}container" . $mode . '.php';

        //create container if not exist
        if (!is_file($cachedContainerFile)) {
            $configFile = __DIR__ . '/../config/setting.yml';
            Assertion::file($configFile, ' the ' . $configFile . ' found.');
            $config = Yaml::parse(file_get_contents($configFile));
            $container = new ContainerBuilder(new ParameterBag());
            $container->register(MySqlAggregateStreamStrategy::class);
            $container->register(FQCNMessageFactory::class);
            $container->register(TimeService::class)->setPublic(true);
            $container->register(\PDO::class, \PDO::class)
                ->addArgument($config['mysql'][strtolower($mode)]['uri'])
                ->addArgument($config['mysql'][strtolower($mode)]['user'])
                ->addArgument($config['mysql'][strtolower($mode)]['pass']);
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
            $container->register(CommandBus::class)
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
        // add commands
        $container->set(self::CONSOLE_APPLICATION, new ArrayObject(self::loadConsoleApplications(__DIR__ . '/../')));
        self::addEventSubscribers();
        self::addCommandRoutes($container);
        return new self($container);
    }

    public static function getContainer(): Container
    {
        return self::$containerBuilder;
    }

    private static function addCommandRoutes(Container $container): void
    {
        $configFile = __DIR__ . '/../config/routes.yml';
        Assertion::file($configFile, ' the ' . $configFile . ' found.');
        $routes = Yaml::parse(file_get_contents($configFile));

        $router = new CommandRouter();
        foreach ($routes['routes'] as $key => $route) {
            $handler = new $route['handler']($container->get(PTR::class));
            $router->route($route['name'])->to($handler);
        }
        $router->attachToMessageBus($container->get(CommandBus::class));
    }

    private static function addEventSubscribers(): void
    {
        //@todo : do something here.
    }
}
