<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Exceptions\LikeNotFoundException;
use a3330\pro_php_v2\src\Models\Like;
use PDO;

class LikeRepository extends AbstractRepository implements LikeRepositoryInterface
{


    /**
     * @throws LikeNotFoundException
     */
    public function get(int $id): Like
    {
        $statement = $this->connection->prepare(
            "select * from like where id = :likeId"
        );

        $statement->execute([
            'likeId' => $id
        ]);

        $likeObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$likeObj)
        {
            throw new LikeNotFoundException("Like with id : $id not found");
        }

        return $this->mapLike($likeObj);
    }

    public function mapLike(object $likeObj): Like
    {
        $like = new Like(
            $likeObj->user_id,
            $likeObj->atricle_id,
            $likeObj->count_like);

        $like
            ->setId($likeObj->id);

        return $like;
    }

    /**
     * @throws LikeNotFoundException
     */
    public function getByPostId(int $article_id): Like
    {
        $statement = $this->connection->prepare(
            "select * from like where article_id = :article_id"
        );

        $statement->execute([
            'article_id' => $article_id
        ]);

        $likeObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$likeObj)
        {
            throw new LikeNotFoundException("Like with article : $article_id not found");
        }

        return $this->mapLike($likeObj);
    }
}