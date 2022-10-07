<?php

use a3330\pro_php_v2\src\Commands\CreateArticleCommand;
use a3330\pro_php_v2\src\Commands\CreateArticleCommandInterface;
use a3330\pro_php_v2\src\Commands\CreateCommentCommand;
use a3330\pro_php_v2\src\Commands\CreateCommentCommandInterface;
use a3330\pro_php_v2\src\Commands\CreateUserCommand;
use a3330\pro_php_v2\src\Commands\CreateUserCommandInterface;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Container\DiContainer;
use a3330\pro_php_v2\src\Handlers\ArticleSearchHandler;
use a3330\pro_php_v2\src\Handlers\ArticleSearchHandlerInterface;
use a3330\pro_php_v2\src\Handlers\CommentSearchHandler;
use a3330\pro_php_v2\src\Handlers\CommentSearchHandlerInterface;
use a3330\pro_php_v2\src\Handlers\UserSearchHandler;
use a3330\pro_php_v2\src\Handlers\UserSearchHandlerInterface;
use a3330\pro_php_v2\src\Repositories\UserRepository;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../database/config/config.php';

Dotenv::createImmutable(__DIR__.'/../')->safeLoad();

$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE, file_get_contents('php://input'));
$container = new DiContainer();

$container->bind(PDO::class, new PDO(databaseConfig()['sqlite']['DATABASE_URL']));
$container->bind(
    SqLiteConnector::class, new SqLiteConnector(databaseConfig()['sqlite']['DATABASE_URL']));

$logger = new Logger('pro_php_v2_logger');

$isNeedLogToFile = $_SERVER['LOG_TO_FILE'] === 'true';
$isNeedLogToConsole = $_SERVER['LOG_TO_CONSOLE'] === 'true';

if($isNeedLogToFile)
{
    $logger->pushHandler(new StreamHandler(
        __DIR__ . '/../var/log/pro_php_v2.log',
        Level::Info
    ));
}

if($isNeedLogToConsole)
{
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(LoggerInterface::class, $logger);

$container->bind(ConnectorInterface::class, SqLiteConnector::class);
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(UserSearchHandlerInterface::class, UserSearchHandler::class);
$container->bind(ArticleSearchHandlerInterface::class, ArticleSearchHandler::class);
$container->bind(CommentSearchHandlerInterface::class, CommentSearchHandler::class);
$container->bind(CreateUserCommandInterface::class, CreateUserCommand::class);
$container->bind(CreateArticleCommandInterface::class, CreateArticleCommand::class);
$container->bind(CreateCommentCommandInterface::class, CreateCommentCommand::class);

return $container;