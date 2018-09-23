<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Commands\PostCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddPostCli extends SocialNetwork
{
    protected function configure():void
    {
        $this
            ->setName('post')
            ->setDescription('add new post to wall')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('sign', InputArgument::REQUIRED)
            ->addArgument('message', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output):void
    {
        $this->commandBus->dispatch(new PostCommand(
            $input->getArgument('username'),
            $input->getArgument('message')
        ));
        $output->writeln('<info> posted to wall of ' . $input->getArgument('username') . ' successfully </info>');
    }
}
