<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Entities\AuthToken;
use a3330\pro_php_v2\src\Exceptions\AuthTokenRepositoryException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Repositories\AuthTokenRepositoryInterface;
use DateTimeInterface;
use PDO;
use PDOException;

class CreateAuthTokenCommand implements CreateAuthTokenCommandInterface
{
    private PDO $connection;
    public function __construct(
        private AuthTokenRepositoryInterface $userRepository,
        private ConnectorInterface $connector
    )
    {
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws CommandException|AuthTokenRepositoryException
     */
    public function handle(AuthToken $authToken): void
    {
        $query = "
               insert into auth_token (token, user_id, expires_at)
               values (:token, :user_id, :expires_at) ON CONFLICT (token) DO UPDATE SET
               expires_at = :expires_at
        ";

        try {
            $statement = $this->connection->prepare($query);

            $statement->execute(
                [
                    ':token' => $authToken->getToken(),
                    ':user_id' => $authToken->getUser()->getId(),
                    ':expires_at' => $authToken->getExpiresAt()->format(DateTimeInterface::ATOM)
                ]
            );
        }catch (PDOException $exception)
        {
            throw new AuthTokenRepositoryException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}