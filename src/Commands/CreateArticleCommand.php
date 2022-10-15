<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class CreateArticleCommand implements CreateArticleCommandInterface
{
    private PDO $connection;

    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private UserRepositoryInterface $userRepository,
        private ConnectorInterface $connector,
        private LoggerInterface $logger,
    )

    {
        $this->connection = $this->connector->getConnection();
    }

    public function handle(Argument $argument): void
    {
        $this->logger->info("Create article command started");

        $author = $argument->get('author');
        $title = $argument->get('title');
        $text = $argument->get('text');

        if ($this->articleExist($title))
        {
            $this->logger->warning("Title already exists: $title");
            return;
        }

        if ($this->articleExist($text))
        {
            $this->logger->warning("Text already exists: $text");
            return;
        }

        if ($this->userAlready($author)){
            $this->logger->warning("User already exist: $author");
            return;
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO post (author, title, text)
                    VALUES (:author, :title, :text)
                  '
        );

        $statement->execute(
            [
                ':author' =>$author,
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

    private function userAlready(int $author): bool
    {
        try {
            $this->userRepository->get($author);
        } catch (UserNotFoundException $exception) {
            return false;
        }

        return true;
    }

}