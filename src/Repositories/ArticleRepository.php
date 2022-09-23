<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Article;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use PDO;

class ArticlesRepository implements ArticlesRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    public function save(Article $article): void
    {
        $statement = $this->connection->prepare(
            '
                    INSERT INTO post (author_id, title, text)
                    VALUES (:author_id, :title, :text)
                  '
        );

        $statement->execute(
            [
                ':author_id' =>$article->getAuthorId(),
                ':title' => $article->getTitle(),
                ':text' => $article->getDescription()
            ]
        );
    }

    public function get(int $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM post WHERE id = :postId'
        );

        $statement->execute([
            'postId' => $id
        ]);

        $articleObj = $statement->fetch(PDO::FETCH_OBJ);

        if (!$articleObj)
        {
            throw new ArticleNotFoundException ("Article with id:$id not found");
        }

        $article = new Article
        (
            $articleObj->author_id,
            $articleObj->title,
            $articleObj->description

        );

        $article ->setId($articleObj->id);

        return $article;
    }
}