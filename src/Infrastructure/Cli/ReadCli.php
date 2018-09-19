<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Services\TimeService;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\TimelineRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

class ReadCli extends SocialNetworkCli
{
    /**
     * @var TimelineRepository
     */
    private $container;

    public function __construct(Container $container)
    {
        parent::__construct(null);
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('read somebody \'s wall')
            ->addArgument('username', InputArgument::REQUIRED, 'the name of user');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $username = $input->getArgument('username');
        $result = $this->container->get(TimelineRepository::class)->findByIndex('username_index', $username);
        if (!empty($result)) {
            $output->success($result, $this->container->get(TimeService::class));
        }
        $output->failed($username);
    }
}
