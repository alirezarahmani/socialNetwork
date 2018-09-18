<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli;

use Boot\SocialNetwork;
use SocialNetwork\Application\Storage\MemcachedCacheStorage;
use SocialNetwork\Infrastructure\Repositories\NonPersistence\WallRepository;
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
        $index =new WallRepository(SocialNetwork::getContainer()->get(MemcachedCacheStorage::class));
        $result = $index->findByIndex('username_index', $input->getArgument('username'));
        if (empty($result)) {
            $output->writeln('<error>' . $input->getArgument('username') . ' has not posted yet!</error>');
            return;
        }
        foreach ($result as $key => $value) {
            $data = is_array($value) ? implode(' ', $value) : $value;
            $output->writeln('<info> | ' . $data);

        }
    }
}
