<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use Exception;

class ArticleSearchHandler implements ArticleSearchHandlerInterface
{
    public function __construct(public ArticlesRepositoryInterface $articlesRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $title = $request->query('title');
        } catch (Exception $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        try {
            $article = $this->articlesRepository->findArticleByTitle($title);
        } catch (ArticleNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'title' => $article->getTitle(),
                'description'=> $article->getDescription()
            ]
        );
    }
}