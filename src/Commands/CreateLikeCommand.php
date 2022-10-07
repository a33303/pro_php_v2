<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\LikeNotFoundException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\LikeRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use PDO;
use Psr\Log\LoggerInterface;

class CreateLikeCommand extends CreateLikeCommandInterface
{
    private PDO $connection;

    public function __construct(
        public LikeRepositoryInterface $likeRepository,
        public ArticlesRepositoryInterface $articlesRepository,
        public UserRepositoryInterface $userRepository,
        private ConnectorInterface $connector,
        private LoggerInterface $logger
    )

    {
        $this->connection = $this->connector->getConnection();
    }


    public function handle(Argument $argument): void
    {
        $this->logger->info("Start like!");

        $user_id =$argument->get('user_id');
        $article_id = $argument->get('article_id');
        $count_like = $argument->get('count_like');


        if ($this->userAlready($user_id)){
            $this->logger->warning("User already exist: $user_id");
            return;
        }

        if ($this->articleAlready($article_id)){
            $this->logger->warning("Article already exist: $article_id");
            return;
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO like (user_id, article_id, count_like)
                    VALUES (:user_id, :article_id, :count_like)
                  '
        );

        $statement->execute(
            [
                ':user_id' => $user_id,
                ':article_id' => $article_id,
                'count_like' => $count_like
            ]
        );

        $this->logger->info("Like created: $article_id"); //??$count_like??
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

    private function userAlready(int $user_id): bool
    {
        try {
            $this->userRepository->get($user_id);
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