<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use PDO;
use Psr\Log\LoggerInterface;

class CreateArticleCommand extends CreateArticleCommandInterface
{
    private PDO $connection;

    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        public UserRepositoryInterface $userRepository,
        private ConnectorInterface $connector,
        private LoggerInterface $logger,
    )

    {
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws CommandException
     */
    public function handle(Argument $argument): void
    {
        $this->logger->info("Create article command started");

        $author_id = $argument->get('author_id');
        $title = $argument->get('title');
        $text = $argument->get('text');

        if ($this->articleExist($title))
        {
            $this->logger->warning("Title already exists: $title");
            return;
            //throw new CommandException("Article already exist: $title".PHP_EOL);
        }

        if ($this->articleExist($text))
        {
            $this->logger->warning("Text already exists: $text");
            return;
        }

        if ($this->userAlready($author_id)){
            $this->logger->warning("User already exist: $author_id");
            return;
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO post (author_id, title, text)
                    VALUES (:author_id, :title, :text)
                  '
        );

        $statement->execute(
            [
                ':author_id' =>$author_id,
                ':title' => $title,
                ':text' => $text
            ]
        );

        $this->logger->info("Article created: $text");
    }

    private function articleExist(string $title): bool
    {
        try {
            $this->articlesRepository->findArticleByTitle($title);
        } catch (ArticleNotFoundException $exception) {
            return false;
        }

        return true;
    }

    private function userAlready(int $author_id): bool
    {
        try {
            $this->userRepository->get($author_id);
        } catch (UserNotFoundException $exception) {
            return false;
        }

        return true;
    }

}