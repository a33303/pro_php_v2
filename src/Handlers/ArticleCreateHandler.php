<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Authentification\AuthentificationInterface;
use a3330\pro_php_v2\src\Commands\CreateArticleCommandInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use Exception;
use HttpException;
use Psr\Log\LoggerInterface;

class ArticleCreateHandler implements ArticleCreateHandlerInterface
{
    public function __construct(
        private CreateArticleCommandInterface $createArticleCommand,
        private AuthentificationInterface $identification,
        private UserRepositoryInterface $userRepository,
        private ArticlesRepositoryInterface $articlesRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws HttpException
     */
    public function handle(Request $request): AbstractResponse
    {
        $title = $request->jsonBodyField('title');
        try {
            $argument = new Argument([
                'title' => $request->jsonBodyField('title'),
                'text' => $request->jsonBodyField('text'),
                'author' => $this->identification->user($request)
            ]);

            $this->createArticleCommand->handle($argument);

        }catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage());
            return  new ErrorResponse($exception->getMessage());
        }


        try {
            $article = $this->articlesRepository->findArticleByTitle($title);
        }catch (ArticleNotFoundException $exception)
        {
            $this->logger->error($exception->getMessage());
            return new ErrorResponse($exception->getMessage());
        }

        try {
            $author = $this->identification->user($request);
        }catch (UserNotFoundException $exception)
        {
            $this->logger->error($exception->getMessage());
            return new ErrorResponse($exception->getMessage());
        }
        $this->logger->info('Article created : '. $article->getId());

        return new SuccessResponse(
            [
                'author' => $article->getAuthor(),
                'title' => $article->getTitle(),
                'text' => $article->getDescription()
            ]
        );
    }
}