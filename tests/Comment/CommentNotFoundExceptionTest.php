<?php

namespace Test\Comment;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Repositories\CommentRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CommentNotFoundExceptionTest extends TestCase
{

    public function testItThrowsAnExceptionWhenCommentAlreadyExists(): void
    {
        $arguments = new Argument(["text" => '12345']);
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage("Comment with id: text not found");
        $arguments->get('text');
    }

    public function testItConvertsCommentToStrings($inputValue, $expectedValue): void
    {
        $argument = new Argument([
            'some_text' => $inputValue
        ]);
        $value = $argument->get(
            'some_text'
        );
        $this->assertSame($expectedValue, $value);
    }

    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementStub);
        $repository = new CommentRepository($connectionStub);
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment');
        $repository->get('some_text');

    }
}