<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Authentification\AuthentificationInterface;
use a3330\pro_php_v2\src\Commands\CreateUserCommandInterface;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use Exception;
use HttpException;
use Psr\Log\LoggerInterface;

class UserCreateHandler implements ArticleCreateHandlerInterface
{
    public function __construct(
        private CreateUserCommandInterface $createUserCommand,
        private AuthentificationInterface $identification,
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws HttpException
     */
    public function handle(Request $request): AbstractResponse
    {
        $email = $request->jsonBodyField('email');
        try {
            $argument = new Argument([
                'email' => $request->jsonBodyField('email'),
                'firstName' => $request->jsonBodyField('firstName'),
                'lastName' => $request->jsonBodyField('lastName'),
                'password' => $request->jsonBodyField('password'),
                'author' => $this->identification->user($request)
            ]);

            $this->createUserCommand->handle($argument);

        }catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage());
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $user = $this->userRepository->findUserByEmail($email);
        }catch (UserNotFoundException $exception)
        {
            $this->logger->error($exception->getMessage());
            return new ErrorResponse($exception->getMessage());
        }

        $this->logger->info('User created : '. $user->getId());

        return new SuccessResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getFirstName() . ' ' . $user->getLastName()
            ]
        );
    }
}