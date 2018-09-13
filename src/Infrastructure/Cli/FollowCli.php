<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FollowCli extends Command
{
    protected function configure()
    {
        $this
            ->setName('')
            ->setDescription('read somebody wall')
            ->addArgument('username', InputArgument::REQUIRED, 'the name of user')
            ->addArgument('follows', InputArgument::REQUIRED)
            ->addArgument('otherUsername', InputArgument::REQUIRED, 'the name of other user');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $otherUsername = $input->getArgument('otherUsername');
    }
}
