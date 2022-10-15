<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Date\DateTime;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Models\User;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use PDO;

class CreateUserCommand implements CreateUserCommandInterface
{
    private PDO $connection;
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ConnectorInterface $connector
    )
    {
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws CommandException
     */
    public function handle(Argument $argument): void
    {
        $email = $argument->get('email');
        $firstName = $argument->get('firstName');

        $lastName = $argument->get('lastName');

        $password = $argument->get('password');
        $hashPassword = hash('sha256', $email . $password);

        if($this->userExist($email))
        {
            throw new CommandException("User already exist: $email".PHP_EOL);
        }

        $statement = $this->connection->prepare(
            '
                    insert into user (email, first_name, last_name, author_id, password, created_at)
                    values (:email, :first_name, :last_name, :author, :password, :created_at)
                  '
        );

        /** @var User $author */
        $author = $argument->get('author');

        $statement->execute(
            [
                ':email' => $email,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':author' => $author->getId(),
                ':password' => $hashPassword,
                ':created_at' => new DateTime()
            ]
        );
    }

    private function userExist(string $email): bool
    {
        try {
            $this->userRepository->findUserByEmail($email);
        }catch (UserNotFoundException $exception)
        {
            return false;
        }

        return true;
    }
}