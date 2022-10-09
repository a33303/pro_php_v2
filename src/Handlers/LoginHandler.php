<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Authentification\PasswordAuthentification;
use a3330\pro_php_v2\src\Commands\CreateAuthTokenCommandInterface;
use a3330\pro_php_v2\src\Entities\AuthToken;
use a3330\pro_php_v2\src\Exceptions\AuthException;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use DateTimeImmutable;
use Exception;

class LoginHandler implements LoginHandlerInterface
{
    public function __construct(
        private PasswordAuthentification $passwordAuthentication,
        private CreateAuthTokenCommandInterface $createAuthTokenCommand,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request): AbstractResponse
    {
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authToken = new AuthToken(
            bin2hex(random_bytes(40)),
            $user,
            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->createAuthTokenCommand->handle($authToken);

        return new SuccessResponse([
            'token' => $authToken->getToken(),
        ]);
    }

}