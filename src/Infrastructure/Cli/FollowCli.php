<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\MySqlEventStore;
use SocialNetwork\Application\Commands\FollowCommand;
use SocialNetwork\Domain\Handlers\FollowHandler;
use SocialNetwork\Infrastructure\Repositories\Persistence\TimelineRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FollowCli extends SocialNetworkCli
{
    protected function configure()
    {
        $this
            ->setName('')
            ->setDescription('read somebody wall')
            ->addArgument('username', InputArgument::REQUIRED, 'the name of user')
            ->addArgument('sign', InputArgument::REQUIRED)
            ->addArgument('follows', InputArgument::REQUIRED, 'the name of other user');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var MySqlEventStore $eventStore */
        $eventStore = SocialNetwork::getContainer()->get(MySqlEventStore::class);
        $this->router->route(FollowCommand::class)->to(new FollowHandler(new TimelineRepository($eventStore)));
        $this->router->attachToMessageBus($this->commandBus);
        $this->commandBus->dispatch(new FollowCommand(
            $input->getArgument('username'),
            $input->getArgument('otherUsername')
        ));

        $output->writeln(
            '<info> Well done! The '. $input->getArgument('username') .' now, is following ' . $input->getArgument('otherUsername') . ' </info>'
        );
    }
}
