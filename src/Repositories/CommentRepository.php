<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Models\Comment;
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

    public function get(int $id): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comment WHERE message_text = :commentId'
        );

        $statement->execute([
            'commentId' => $id
        ]);

        $commentObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$commentObj) {
            throw new CommentNotFoundException ("Comment with $id not found");
        }

        return $this->mapComment($commentObj);
    }

        public function mapComment(object $commentObj): Comment
    {
        $comment = new Comment
        (
            $commentObj->post_id,
            $commentObj->author_id,
            $commentObj->text

        );

        $comment->setId($commentObj->id);
        return $comment;
    }

    public function findCommentByText(string $text): Comment
    {
        $statement = $this->connection->prepare(
            "select * from user where text = :text"
        );

        $statement->execute([
            'text' => $text
        ]);

        $commentObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$commentObj)
        {
            throw new CommentNotFoundException("User with text : $text not found");
        }

        return $this->mapComment($commentObj);
    }
}