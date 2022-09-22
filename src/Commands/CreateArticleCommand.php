<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Models\Article;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use PDO;

class CreateArticleCommand extends CreateArticleCommandInterface
{
    private PDO $connection;

    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private ?ConnectorInterface $connector = null)

    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    public function handle(Argument $argument): void
    {
        $author_id = $argument->get('author_id');
        $title = $argument->get('title');
        $text = $argument->get('text');

        if ($this->articleExist($title))
        {
            throw new CommandException("Article already exist: $title".PHP_EOL);
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO post (author_id, title, text)
                    VALUES (:author_id, :title, :text)
                  '
        );

        $statement->execute(
            [
                ':author_id' =>$author_id,
                ':title' => $title,
                ':text' => $text
            ]
        );
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
}