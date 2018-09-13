<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WallCli extends Command
{
    protected function configure()
    {
        $this
            ->setName('')
            ->setDescription('wall of someone')
            ->addArgument('username', InputArgument::REQUIRED, 'the name of user')
            ->addArgument('wall', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
    }
}
