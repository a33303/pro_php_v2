<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Date\DateTime;
use a3330\pro_php_v2\src\Entities\AuthToken;
use a3330\pro_php_v2\src\Exceptions\AuthTokenNotFoundException;

use PDO;

class AuthTokenRepository extends AbstractRepository implements AuthTokenRepositoryInterface
{
    public function __construct(
        ConnectorInterface $connector,
        private UserRepositoryInterface $userRepository
    )
    {
        parent::__construct($connector);
    }

    public function getToken(string $token): AuthToken
    {
        $statement = $this->connection->prepare(
            "select * from auth_token where token = :token"
        );

        $statement->execute([
            'token' => $token
        ]);

        $authTokenObject = $statement->fetch(PDO::FETCH_OBJ);

        if(!$authTokenObject)
        {
            throw new AuthTokenNotFoundException("Auth token with token : $token not found");
        }

        return $this->mapAuthToken($authTokenObject);
    }

    public function mapAuthToken(object $authTokenObject): AuthToken
    {
        return new AuthToken(
            $authTokenObject->token,
            $this->userRepository->get($authTokenObject->user_id),
            new DateTime($authTokenObject->expires_at)
        );
    }
}