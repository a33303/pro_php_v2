<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Models\Comment\Post;
use PDO;

class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{

    public function get(int $id): Post
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

        public function mapComment(object $commentObj): Post
    {
        $comment = new Post
        (
            $commentObj->post_id,
            $commentObj->author,
            $commentObj->text

        );

        $comment->setId($commentObj->id);
        return $comment;
    }

    public function findCommentByText(string $text): Post
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