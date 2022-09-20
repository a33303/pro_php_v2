<?php

namespace Test\Comment;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Exceptions\CommentNotFoundException;
use a3330\pro_php_v2\src\Models\Comment;
use PDO;
use PHPUnit\Framework\TestCase;

class CommentNotFoundExceptionTest extends TestCase
{
    private PDO $connection;

    public function testItThrowsAnExceptionWhenArticleAlreadyExists(): void
    {
        $arguments = new Argument(["text"]);
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage("Comment with id: text not found");
        $arguments->get('text');
    }
}