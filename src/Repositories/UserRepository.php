<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Date\DateTime;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Models\User\User;
use Exception;
use PDO;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function get(int $id): User
    {
        $statement = $this->connection->prepare(
            "select * from user where id = :userId"
        );

        $statement->execute([
            'userId' => $id
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with id : $id not found");
        }

        return $this->mapUser($userObj);
    }

    /**
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function findUserByEmail(string $email): User
    {
        $statement = $this->connection->prepare(
            "select * from user where email = :email"
        );

        $statement->execute([
            'email' => $email
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with email : $email not found");
        }

        return $this->mapUser($userObj);
    }

    public function mapUser(object $userObj): User
    {
        $author = $userObj->author_id ?  $this->get($userObj->author_id) : null;

        $user = new User(
            $userObj->email,
            $userObj->first_name,
            $userObj->last_name,
            $userObj->password,
            $author
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