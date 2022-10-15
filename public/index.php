<?php

/** @var ContainerInterface $container */
/** @var Request $request */

use a3330\pro_php_v2\src\Handlers\ArticleCreateHandlerInterface;
use a3330\pro_php_v2\src\Handlers\UserSearchHandlerInterface;
use a3330\pro_php_v2\src\Request\Request;
use Psr\Container\ContainerInterface;


require_once __DIR__ . '/autoload_runtime.php';

//$userRepository = new UserRepository();
//$command = new CreateUserCommand($userRepository);
//
//try{
//    $command->handle($argv);
//} catch (CommandException $commandException)
//{
//    echo $commandException->getMessage();
//}

/** @var UserSearchHandlerInterface $handler */
$handler = $container->get(ArticleCreateHandlerInterface::class);
$handler->handle($request);
