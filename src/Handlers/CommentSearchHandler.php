<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Repositories\CommentRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use Exception;

class CommentSearchHandler implements CommentSearchHandlerInterface
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $text = $request->query('text');
        } catch (Exception $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }
        try {
            $comment = $this->commentRepository->findCommentByText($text);
        } catch (CommentNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'author' => $comment->getAuthor(),
                'text' => $comment->getText()
            ]
        );
    }
}