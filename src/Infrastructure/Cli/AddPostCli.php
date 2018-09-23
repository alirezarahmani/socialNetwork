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
            ->addArgument('sign', InputArgument::REQUIRED)
            ->addArgument('message', InputArgument::REQUIRED, 'the name of user');
    }

    public function execute(InputInterface $input, OutputInterface $output):void
    {
        $this->commandBus->dispatch(new PostCommand(
            $input->getFirstArgument(),
            $input->getArgument('message')
        ));
        $output->writeln('<info> Well done! The post is on the wall of ' . $input->getFirstArgument() . ' </info>');
    }
}
