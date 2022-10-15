<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Models\Comment;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\CommentRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class CreateCommentCommand extends CreateCommentCommandInterface
{
    private PDO $connection;

    public function __construct(
        public CommentRepositoryInterface $commentRepository,
        public UserRepositoryInterface $userRepository,
        public ArticlesRepositoryInterface $articlesRepository,
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
        $this->logger->info("Create comment command started");

        $author_id = $argument->get('author_id');
        $article_id = $argument->get('article_id');
        $text = $argument->get('text');

        if ($this->commentExist($text))
        {
            $this->logger->warning("Comment already exists: $text");
            return;
            //throw new CommandException("Comment already exist: $text".PHP_EOL);
        }

        if ($this->userAlready($author_id)){
            $this->logger->warning("User already exist: $author_id");
            return;
        }

        if ($this->articleAlready($article_id)){
            $this->logger->warning("Article already exist: $article_id");
            return;
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO comment (post_id, author_id, text)
                    VALUES (:post_id, :author_id, :text)
                  '
        );

        $statement->execute(
            [
                ':post_id' => $article_id,
                ':author_id' => $author_id,
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

    private function userAlready(int $author_id): bool
    {
        try {
            $this->userRepository->get($author_id);
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