<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Authentification\AuthentificationInterface;
use a3330\pro_php_v2\src\Commands\CreateArticleCommandInterface;
use a3330\pro_php_v2\src\Commands\CreateLikeCommandInterface;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\UserNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\LikeRepositoryInterface;
use a3330\pro_php_v2\src\Repositories\UserRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use a3330\pro_php_v2\src\Traits\Id;
use Exception;
use HttpException;
use Psr\Log\LoggerInterface;

class LikeCreateHandler implements LikeCreateHandlerInterface
{
    public function __construct(
        public CreateLikeCommandInterface $createLikeCommand,
        private AuthentificationInterface $identification,
        public UserRepositoryInterface $userRepository,
        public LikeRepositoryInterface $likeRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws HttpException
     */
    public function handle(Request $request): AbstractResponse
    {
        $like = $request->jsonBodyField('count_like');
        try {
            $argument = new Argument([
                'count_like' => $request->jsonBodyField('count_like'),
                'article_id' => $request->jsonBodyField('article_id'),
                'user_id' => $this->identification->user($request)
            ]);

            $this->createLikeCommand->handle($argument);

        }catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage());
            return  new ErrorResponse($exception->getMessage());
        }


        try {
            $like = $this->likeRepository->getByPostId($like);
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
        $this->logger->info('Like created : '. $like->getId());

        return new SuccessResponse(
            [
                'count_like' => $like->getCountLike(),
                'article_id' => $like->getArticleId() . ' ' . $like->getUserId($author)
            ]
        );
    }
}