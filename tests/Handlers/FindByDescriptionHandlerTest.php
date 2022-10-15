<?php

namespace Test\Handlers;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Handlers\ArticleSearchHandler;
use a3330\pro_php_v2\src\Handlers\ArticleSearchHandlerInterface;
use a3330\pro_php_v2\src\Repositories\ArticleRepository;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Models\Article;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use Dotenv\Dotenv;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FindByDescriptionHandlerTest extends TestCase
{
    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        public ?ArticlesRepositoryInterface $articlesRepository = null,
        public ?ArticleSearchHandlerInterface $articleSearchHandler = null
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


        $this->articlesRepository ??= new ArticleRepository($connector);
        $this->articleSearchHandler =$this->articlesRepository ?? new ArticleSearchHandler(
                $this->articlesRepository,
                $logger
            );
        parent::__construct($name, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoTitleProvided(): void
    {
        $request = new Request([], []);
        $response = $this->articleSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: description"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfArticleNotFound(): void
    {
        $request = new Request(['description' => '123456'], []);
        $response = $this->articlesRepository->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"Article with description : 123456 not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['description' => '12345'], []);
        $response = $this->articlesRepository->handle($request);

        $this->assertInstanceOf(SuccessResponse::class, $response);
        $this->expectOutputString(
            '{"success":true,"data":{"title":"hello world","description":"12345"}}');

        echo json_encode($response);
    }


    private function usersRepository(array $articles): ArticlesRepositoryInterface
    {
        return new class($articles) implements ArticlesRepositoryInterface {
            public function __construct(
                public array $articles
            ) {
            }

            public function save(Article $articles): void
            {
            }

            public function get(int $id): Article
            {
                throw new ArticleNotFoundException("Not found");
            }

            public function findArticleByDescription(string $description): Article
            {
                foreach ($this->articles as $article) {
                    if ($article instanceof Article && $description === $article->getDescription()) {
                        return $article;
                    }
                }

                throw new ArticleNotFoundException("Not found");
            }


            public function findArticleByTitle(string $title): Article
            {
                foreach ($this->articles as $article) {
                    if ($article instanceof Article && $title === $article->getTitle()) {
                        return $article;
                    }
                }

                throw new ArticleNotFoundException("Not found");
            }
        };
    }
}