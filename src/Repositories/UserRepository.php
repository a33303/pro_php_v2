<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Date\DateTime;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\User;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }


    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            '
                    INSERT INTO user (first_name, last_name, created_at)
                    VALUES (:first_name, :last_name, :created_at)
                  '
        );

        $statement->execute(
            [
                ':first_name' => $user->getFirstName(),
                ':last_name' => $user->getLastName(),
                ':created_at' => $user->getCreatedAt()
            ]
        );
    }

    /**
     * @throws UserNotFoundException
     * @throws \Exception
     */
    public function get(int $id): User
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM user WHERE id = :userId"
        );

        $statement->execute([
            'userId' => $id
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with id:$id not found");
        }

        $user = new User
        (
            $userObj->first_name,
            $userObj->last_name
        );

        $user
            ->setId($userObj->id)
            ->setActive($userObj->active)
            ->setCreatedAt(new DateTime($userObj->created_at))
            ->setUpdatedAt(($updatedAt = $userObj->updated_at) ? new DateTime($updatedAt) : null)
            ->setDeletedAt(($deletedAt = $userObj->deleted_at) ? new DateTime($deletedAt) : null);

        return $user;

    }
}