<?php

namespace a3330\pro_php_v2\src\Authentification;

use a3330\pro_php_v2\src\Exceptions\AuthException;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Models\User;
use HttpException;
use InvalidArgumentException;

class JsonBodyAuthentification implements AuthentificationInterface
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
            return $this->userRepository->findUserByEmail($email);
        }catch (HttpException|InvalidArgumentException $exception)
        {
            throw new AuthException($exception->getMessage());
        }
    }
}