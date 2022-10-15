<?php

use a3330\pro_php_v2\src\Console\CreateCommentCommand;
use a3330\pro_php_v2\src\Console\CreateUserCommand;
use a3330\pro_php_v2\src\Console\FakeCommand\PopulateDbCommand;
use Symfony\Component\Console\Application;

$container = require_once __DIR__ . '/autoload_runtime.php';

$application = new Application();

$commandClasses = [
    CreateUserCommand::class,
    CreateCommentCommand::class,
    PopulateDbCommand::class
];

foreach ($commandClasses as $commandClass)
{
    $command = $container->get($commandClass);
    $application->add($command);
}

$application->run();
