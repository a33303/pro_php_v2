<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Models\Comment;
use a3330\pro_php_v2\src\Repositories\CommentRepositoryInterface;
use PDO;

class CreateCommentCommand extends CreateCommentCommandInterface
{
    private PDO $connection;

    public function __construct(
        public CommentRepositoryInterface $commentRepository,
        private ConnectorInterface $connector
    )
    {
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws CommandException
     */
    public function handle(Argument $argument): void
    {
        $author_id = $argument->get('author_id');
        $article_id = $argument->get('article_id');
        $text = $argument->get('text');

        if ($this->commentExist($text))
        {
            throw new CommandException("Comment already exist: $text".PHP_EOL);
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
}