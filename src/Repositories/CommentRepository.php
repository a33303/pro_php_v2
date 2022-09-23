<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Comment;
use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use PDO;

class CommentRepository implements CommentRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            '
                    INSERT INTO comment (post_id, author_id, text)
                    VALUES (:post_id, :author_id, :text)
                  '
        );

        $statement->execute(
            [
                ':post_id' => $comment->getArticlesId(),
                ':author_id' => $comment->getAuthorId(),
                ':text' => $comment->getText()
            ]
        );
    }

    public function get(int $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comment WHERE id = :commentId'
        );

        $statement->execute([
            'commentId' => $id
        ]);

        $commentObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$commentObj)
        {
            throw new CommentNotFoundException ("Comment with id:$id not found");
        }

        $comment = new Comment
        (
            $commentObj->post_id,
            $commentObj->author_id,
            $commentObj->text

        );

        $comment ->setId($commentObj->id);

        return $comment;
    }

}