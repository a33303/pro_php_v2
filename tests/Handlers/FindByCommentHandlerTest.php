<?php

namespace Test\Handlers;

use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Handlers\CommentSearchHandler;
use a3330\pro_php_v2\src\Handlers\CommentSearchHandlerInterface;
use a3330\pro_php_v2\src\Repositories\CommentRepository;
use a3330\pro_php_v2\src\Repositories\CommentRepositoryInterface;
use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Models\Comment;
use a3330\pro_php_v2\src\Response\ErrorResponse;
use a3330\pro_php_v2\src\Response\SuccessResponse;
use PHPUnit\Framework\TestCase;

class FindByCommentHandlerTest extends TestCase
{
    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        public ?CommentRepositoryInterface $commentRepository = null,
        public ?CommentSearchHandlerInterface $commentSearchHandler = null
    )
    {
        $this->commentRepository ??= new CommentRepository();
        $this->commentRepository=$this->articlesRepository ?? new CommentSearchHandler($this->commentRepository);
        parent::__construct($name, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoTitleProvided(): void
    {
        $request = new Request([], []);
        $response = $this->commentSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: text"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfArticleNotFound(): void
    {
        $request = new Request(['text' => 'helo'], []);
        $response = $this->commentRepository->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"Comment with text : helo not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['text' => 'hello'], []);
        $response = $this->commentRepository->handle($request);

        $this->assertInstanceOf(SuccessResponse::class, $response);
        $this->expectOutputString(
            '{"success":true,"data":{"text":"hello"}}');

        echo json_encode($response);
    }


    private function usersRepository(array $comments): CommentRepositoryInterface
    {
        return new class($comments) implements CommentRepositoryInterface {
            public function __construct(
                $comments            ) {
            }

            public function save(Comment $comments): void
            {
            }

            public function get(int $id): Comment
            {
                throw new CommentNotFoundException("Not found");
            }

              public function findCommentByText(string $text): Comment
            {
                foreach ($this->comments as $comment) {
                    if ($comment instanceof Comment && $text === $comment->getText()) {
                        return $comment;
                    }
                }

                throw new CommentNotFoundException("Not found");
            }
        };
    }
}