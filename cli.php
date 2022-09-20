<?php

use a3330\pro_php_v2\src\Commands\CreateUserCommand;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Repositories\UserRepository;
use a3330\pro_php_v2\src\Repositories\ArticleRepository;
use a3330\pro_php_v2\src\Repositories\AbstractRepository;
use a3330\pro_php_v2\src\Repositories\CommentRepository;
use  a3330\pro_php_v2\src\Models\User;
use  a3330\pro_php_v2\src\Models\Comment;
use  a3330\pro_php_v2\src\Models\Article;

require_once __DIR__ . '/autoload_runtime.php';

$userRepository = new UserRepository();
$command = new CreateUserCommand($userRepository);

try{
    $command->handle($argv);
} catch (CommandException $commandException)
{
    echo $commandException->getMessage();
}