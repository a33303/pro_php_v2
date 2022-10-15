<?php

namespace Test\Handlers;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Handlers\UserSearchHandler;
use a3330\pro_php_v2\src\Handlers\UserSearchHandlerInterface;
use a3330\pro_php_v2\src\Models\User\User;
use a3330\pro_php_v2\src\Repositories\UserRepository;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use Dotenv\Dotenv;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FindByUsernameHandlerTest extends TestCase
{
    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        private ?UserRepositoryInterface $userRepository = null,
        private ?UserSearchHandlerInterface $userSearchHandler = null
    )
    {
        Dotenv::createImmutable(__DIR__.'/../../')->safeLoad();
        $request = new Request($_GET, $_POST, $_SERVER, $_COOKIE);

        $connector = new class() implements ConnectorInterface
        {
            public static function getConnection(): PDO
            {
                return new PDO(databaseConfig()['sqlite']['DATABASE_URL']);
            }
        };

        $logger = new class() implements LoggerInterface
        {
            public function emergency(\Stringable|string $message, array $context = []): void
            {
            }

            public function alert(\Stringable|string $message, array $context = []): void
            {
            }

            public function critical(\Stringable|string $message, array $context = []): void
            {
            }

            public function error(\Stringable|string $message, array $context = []): void
            {
            }

            public function warning(\Stringable|string $message, array $context = []): void
            {
            }

            public function notice(\Stringable|string $message, array $context = []): void
            {
            }

            public function info(\Stringable|string $message, array $context = []): void
            {
            }

            public function debug(\Stringable|string $message, array $context = []): void
            {
            }

            public function log($level, \Stringable|string $message, array $context = []): void
            {
            }
        };


        $this->userRepository ??= new UserRepository($connector);
        $this->userSearchHandler =
            $this->userSearchHandler ?? new UserSearchHandler(
                $this->userRepository,
                $logger
            );
        $this->userSearchHandler->handle($request);
        parent::__construct($name, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        $request = new Request([], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: email"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['email' => 'a33303@ir.ru'], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"User with email : fadeev123123@example.ru not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['email' => 'a33303@ir.ru'], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(SuccessResponse::class, $response);
        $this->expectOutputString(
            '{"success":true,"data":{"email":"fadeev5@example.ru","name":"Georgy Fadeev"}}');

        echo json_encode($response);
    }
}