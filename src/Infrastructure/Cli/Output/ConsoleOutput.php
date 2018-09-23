<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli\Output;

use Assert\Assertion;
use SocialNetwork\Application\Services\ApplicationServiceInterface;
use Symfony\Component\Console\Output\ConsoleOutput as CO;

class ConsoleOutput extends CO
{
    /**
     * @param array                       $result
     * @param ApplicationServiceInterface $service
     *
     * @throws \Assert\AssertionFailedException
     */
    public function asTimeline(array $result, ApplicationServiceInterface $service):void
    {
        if (empty($result)) {
            $this->writeln('<info>nothing to display</info>');
            return;
        }
        Assertion::keyIsset($result, 'username', 'sorry, result is not valid');
        $username = $result[0]['username'];
        $this->writeln('<info> > ' . $username . ':');
        foreach ($result as $key => $value) {
            if ($username != $value['username']) {
                $this->writeln('<info> > ' . $value['username'] . ':');
                $username = $value['username'];
            }
            unset($value['username']);
            $value['createAt'] = $service->elapsed(strtotime($value['createAt']));
            $this->writeln('<info>| ' . is_array($value) ? implode(' ', $value) : $value . '</info>');
        }
    }
}
