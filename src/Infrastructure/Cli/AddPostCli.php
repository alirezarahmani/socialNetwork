<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Commands\PostCommand;
use SocialNetwork\Domain\Handlers\PostHandler;
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
        $this->dispatch($input->getArgument('username'), $input->getArgument('message'));
        $output->writeln('<info>Well done! Added!</info>');
    }

    private function dispatch($username, $message): void
    {
        $this->router->route(PostCommand::class)->to(new PostHandler());
        $this->router->attachToMessageBus($this->commandBus);
        $this->commandBus->dispatch(new PostCommand($username, $message));
    }
}
