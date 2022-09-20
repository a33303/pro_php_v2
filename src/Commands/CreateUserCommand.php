<?php

namespace a3330\pro_php_v2\src\Commands;


use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Connection\SqLiteConnector;
use a3330\pro_php_v2\src\Date\DateTime;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use PDO;

class CreateUserCommand extends CreateUserCommandInterface
{
    private PDO $connection;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ?ConnectorInterface $connector = null
    )
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    public function handle(Argument $argument): void
    {
        $email = $argument->get('email');
        $firstName = $argument->get('firstName');
        $lastName = $argument->get('lastName');

        if ($this->userExist($email))
        {
            throw new CommandException("User already exist: $email".PHP_EOL);
        }

        $statement = $this->connection->prepare(
            '
                    INSERT INTO user (email, first_name, last_name, created_at)
                    VALUES (:email, :first_name, :last_name, :created_at)
                  '
        );

        $statement->execute(
            [
                ':email' => $email,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':created_at' => new DateTime()
            ]
        );
    }

    private function userExist(string $email): bool
    {
        try {
            $this->userRepository->findUserByEmail($email);
        } catch (UserNotFoundException $exception) {
            return false;
        }

        return true;
    }


}