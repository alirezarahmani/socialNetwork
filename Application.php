#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use SocialNetwork\Infrastructure\Cli\Output\ReadCliOutput;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

$container = \Boot\SocialNetwork::getContainer();
$application = new Application();
$application->add(new \SocialNetwork\Infrastructure\Cli\AddPostCli());
$application->add(new \SocialNetwork\Infrastructure\Cli\RunProjectionCli());
$application->add(new \SocialNetwork\Infrastructure\Cli\ReadCli($container))->run(new ArgvInput(), new ReadCliOutput());
$application->run();
