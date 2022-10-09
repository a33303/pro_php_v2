<?php

namespace a3330\pro_php_v2\src\Authentification;

use a3330\pro_php_v2\src\Exceptions\AuthException;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Models\User;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use HttpException;
use InvalidArgumentException;

class PasswordAuthentification implements AuthentificationInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
            $email = $request->jsonBodyField('auth_user');
            $user = $this->userRepository->findUserByEmail($email);
        }catch (HttpException|InvalidArgumentException $exception)
        {
            throw new AuthException($exception->getMessage());
        }

        try {
            $password = $request->jsonBodyField('auth_password');
        }catch (HttpException $exception)
        {
            throw new AuthException($exception->getMessage());
        }
        $hashPassword = hash('sha256', $password);

        if($hashPassword !== $user->getPassword())
        {
            throw new AuthException('Invalid login or password');
        }

        return $user;
    }
}