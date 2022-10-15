<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\CommentRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class CreateCommentCommand implements CreateCommentCommandInterface
{
    private PDO $connection;

    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private UserRepositoryInterface $userRepository,
        private ArticlesRepositoryInterface $articlesRepository,
        private ConnectorInterface $connector,
        private LoggerInterface $logger,
    )
    {
        $this->connection = $this->connector->getConnection();
    }

    public function handle(Argument $argument): void
    {
        $this->logger->info("Create comment command started");

        $author = $argument->get('author');
        $article_id = $argument->get('article_id');
        $text = $argument->get('text');

        if ($this->commentExist($text))
        {
            $this->logger->warning("Comment already exists: $text");
            return;
            //throw new CommandException("Comment already exist: $text".PHP_EOL);
        }

        if ($this->userAlready($author)){
            $this->logger->warning("User already exist: $author");
            return;
        }

        if ($this->articleAlready($article_id)){
            $this->logger->warning("Article already exist: $article_id");
            return;
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO comment (article_id, author, text)
                    VALUES (:article_id, :author, :text)
                  '
        );

        $statement->execute(
            [
                ':article_id' => $article_id,
                ':author_id' => $author,
                ':text' => $text
            ]
        );

        $this->logger->info("Comment created: $text");
    }

    private function commentExist(string $text): bool
    {
        try {
            $this->commentRepository->findCommentByText($text);
        } catch (CommentNotFoundException $exception)
        {
            return  false;
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

    private function articleAlready(int $article_id): bool
    {
        try {
            $this->articlesRepository->get($article_id);
        } catch (ArticleNotFoundException $exception) {
            return false;
        }

        return true;
    }
}