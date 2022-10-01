<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\LikeNotFoundException;
use a3330\pro_php_v2\src\Repositories\LikeRepositoryInterface;
use PDO;

class CreateLikeCommand implements CreateLikeCommandInterface
{
    private PDO $connection;

    public function __construct(
        public LikeRepositoryInterface $likeRepository,
        private ConnectorInterface $connector)

    {
        $this->connection = $this->connector->getConnection();
    }


    public function handle(Argument $argument): void
    {
        $user_id =$argument->get('user_id');
        $article_id = $argument->get('article_id');

        if ($this->likeExist($article_id))
        {
            throw new CommandException("Like already exist: $article_id".PHP_EOL);
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO like (user_id, article_id)
                    VALUES (:user_id, :article_id)
                  '
        );


        $statement->execute(
            [
                ':user_id' => $user_id,
                ':article_id' => $article_id
            ]
        );
    }

    private function likeExist(string $article_id): bool
    {
        try {
            $this->likeRepository->getByPostId($article_id);
        } catch (LikeNotFoundException $exception) {
            return false;
        }

        return true;
    }
}