<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use SocialNetwork\Application\Projections\ReadPostsProjection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCli extends SocialNetworkCli
{
    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('read somebody wall')
            ->addArgument('username', InputArgument::REQUIRED, 'the name of user');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        (new ReadPostsProjection())->byUsername();
        $output->writeln('<info>Well done! the post is on the wall of ' . $input->getArgument('username'). ' </info>');
    }
}
