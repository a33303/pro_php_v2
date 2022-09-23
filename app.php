<?php

require_once __DIR__ . '/vendor/autoload.php';

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Commands\CreateUserCommand;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Repositories\UserRepository;

$userRepository = new UserRepository();
$command = new CreateUserCommand($userRepository);

try{
    $command->handle(Argument::fromArgv($argv));
} catch (CommandException $commandException)
{
    echo $commandException->getMessage();
}

