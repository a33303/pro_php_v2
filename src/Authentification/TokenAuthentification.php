<?php

namespace a3330\pro_php_v2\src\Authentification;

use a3330\pro_php_v2\src\Exceptions\AuthException;
use a3330\pro_php_v2\src\Exceptions\AuthTokenNotFoundException;
use a3330\pro_php_v2\src\Models\User\User;
use a3330\pro_php_v2\src\Repositories\AuthTokenRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use DateTimeImmutable;
use HttpException;
use InvalidArgumentException;

class TokenAuthentification implements AuthentificationInterface
{
    public const PREFIX = 'Bearer ';

    public function __construct(private AuthTokenRepositoryInterface $authTokenRepository
    ) {
    }

    /**
     * @throws AuthException
     */
    public function user(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        }catch (HttpException|InvalidArgumentException $exception)
        {
            throw new AuthException($exception->getMessage());
        }

        if(!str_starts_with($header, self::PREFIX))
        {
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::PREFIX));

        try {
            $authToken = $this->authTokenRepository->getToken($token);

        }catch (AuthTokenNotFoundException $exception)
        {
            throw new AuthException("Bad token: [$token]");
        }

        if($authToken->getExpiresAt() <= new DateTimeImmutable())
        {
            throw new AuthException(("Token expired: [$token]"));
        }

        return $authToken->getUser();
    }
}