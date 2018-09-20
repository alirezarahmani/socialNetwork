<?php
declare(strict_types=1);
namespace SocialNetwork\Infrastructure\Cli\Output;

use SocialNetwork\Application\Services\ApplicationServiceInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class TimelineCliOutput extends ConsoleOutput
{
    /**
     * @param array                       $result
     * @param ApplicationServiceInterface $service
     *
     */
    public function success(array $result, ApplicationServiceInterface $service):void
    {
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

    public function failed(string $username):void
    {
        $this->writeln('<error>' . $username . ' has not posted yet!</error>');
    }
}
