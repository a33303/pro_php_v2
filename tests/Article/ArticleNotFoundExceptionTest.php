<?php

namespace Test\Article;

use a3330\pro_php_v2\src\Argument\Argument;
use a3330\pro_php_v2\src\Commands\CreateArticleCommand;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Exceptions\CommandException;
use a3330\pro_php_v2\src\Repositories\ArticleRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class ArticleNotFoundExceptionTest extends TestCase
{
    public function testArticleAlreadyExist(): void
    {
        $articleCommand = new CreateArticleCommand(
            new DummyArticleRepository()
        );
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage("Article with id: some_key not found");
        $articleCommand->handle(new Argument([
            'author_id' => 1,
            'title' => 'Hello world!',
            'description' => '123'
        ]));
    }

    public function testItConvertsArticleToStrings($inputValue, $expectedValue): void {
        $argument = new Argument([
            'some_title' => $inputValue
        ]);
        $value = $argument->get(
            'some_title'
        );
        $this->assertSame($expectedValue, $value);
    }

    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementStub);
        $repository = new ArticleRepository($connectionStub);
        $this->expectException(ArticleNotFoundException::class);
        $this->expectExceptionMessage('Cannot find article');
        $repository->get("some_key");

    }
}