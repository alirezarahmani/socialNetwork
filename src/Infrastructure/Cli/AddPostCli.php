<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Boot\SocialNetwork;
use Prooph\EventStore\Pdo\MySqlEventStore;
use SocialNetwork\Application\Commands\PostCommand;
use SocialNetwork\Domain\Handlers\AddPostHandler;
use SocialNetwork\Infrastructure\Repositories\Persistence\WallRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddPostCli extends SocialNetworkCli
{
    protected function configure(): void
    {
        $this
            ->setName('posting')
            ->setDescription('add new post to wall')
            ->addArgument('username', InputArgument::REQUIRED, 'the name of user')
            ->addArgument('sign', InputArgument::REQUIRED)
            ->addArgument('message', InputArgument::REQUIRED, 'message to be posted');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $eventStore = SocialNetwork::getContainer()->get(MySqlEventStore::class);
        $this->router->route(PostCommand::class)->to(new AddPostHandler(new WallRepository($eventStore)));
        $this->router->attachToMessageBus($this->commandBus);
        $this->commandBus->dispatch(new PostCommand(
            $input->getArgument('username'),
            $input->getArgument('message')
        ));
        $output->writeln('<info> Well done! The post is on the wall of ' . $input->getArgument('username'). ' </info>');
    }
}
