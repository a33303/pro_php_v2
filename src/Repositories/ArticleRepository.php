<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Models\Article;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Models\User;
use PDO;

class ArticleRepository extends AbstractRepository  implements ArticlesRepositoryInterface
{

    public function get(int $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM post WHERE id = :postId'
        );

        $statement->execute([
            'postId' => $id
        ]);

        $articleObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$articleObj) {
            throw new ArticleNotFoundException ("Article with id:$id not found");
        }

        return $this->mapArticle($articleObj);
    }

    /**
     * @throws ArticleNotFoundException
     * @throws \Exception
     */
    public function findArticleByTitle(string $title): Article
    {
        $statement = $this->connection->prepare(
            "select * from user where title = :title"
        );

        $statement->execute([
            'title' => $title
        ]);

        $articleObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$articleObj)
        {
            throw new ArticleNotFoundException("Article with title : $title not found");
        }

        return $this->mapArticle($articleObj);
    }

    public function mapArticle(object $articleObj): Article
    {
        $article = new Article
        (
            $articleObj->author_id,
            $articleObj->title,
            $articleObj->description

        );

        $article ->setId($articleObj->id);

        return $article;
    }

    /**
     * @throws ArticleNotFoundException
     * @throws \Exception
     */
    public function findArticleByDescription(string $description): Article
    {
        $statement = $this->connection->prepare(
            "select * from user where description = :description"
        );

        $statement->execute([
            'description' => $description
        ]);

        $articleObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$articleObj)
        {
            throw new ArticleNotFoundException("Article with title : $description not found");
        }

        return $this->mapArticle($articleObj);
    }
}