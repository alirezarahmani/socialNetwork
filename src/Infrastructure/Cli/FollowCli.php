<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Commands\FollowCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FollowCli extends SocialNetwork
{
    protected function configure()
    {
        $this
            ->setName('follow')
            ->setDescription('read somebody wall')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('follows', InputArgument::REQUIRED)
            ->addArgument('other_username', InputArgument::REQUIRED, 'the name of other user');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandBus->dispatch(new FollowCommand(
            $input->getArgument('username'),
            $input->getArgument('other_username')
        ));

        $output->writeln(
            '<info>'. $input->getArgument('username') .' now, is following ' . $input->getArgument('other_username') . ' </info>'
        );
    }
}
