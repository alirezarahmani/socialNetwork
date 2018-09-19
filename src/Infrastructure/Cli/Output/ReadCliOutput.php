<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli\Output;

use Assert\Assertion;
use SocialNetwork\Application\Services\ApplicationServiceInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class ReadCliOutput extends ConsoleOutput
{
    /**
     * @param array                       $result
     * @param ApplicationServiceInterface $service
     *
     * @throws \Assert\AssertionFailedException
     */
    public function success(array $result, ApplicationServiceInterface $service):void
    {
        Assertion::keyIsset($result[0], 'username', 'sorry wrong input');
        $this->writeln('<info> > ' . $result[0]['username'] . ':');
        foreach ($result as $key => $value) {
            unset($value['username']);
            $value['createAt'] = $service->elapsed(strtotime($value['createAt']));
            $data = is_array($value) ? implode(' ', $value) : $value;
            $this->writeln('<info> | ' . $data);

        }
    }

    public function failed(string $username)
    {
        $this->writeln('<error>' . $username . ' has not posted yet!</error>');
    }
}
