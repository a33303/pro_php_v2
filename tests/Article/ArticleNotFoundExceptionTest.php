<?php

namespace Test\Article;

use a3330\pro_php_v2\src\Arguments\Argument;
use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use PHPUnit\Framework\TestCase;

class ArticleNotFoundExceptionTest extends TestCase
{
    public function testItThrowsAnExceptionWhenArticleAlreadyExists(): void
    {
        $arguments = new Argument([]);
        $this->expectException(ArticleNotFoundException::class);
        $this->expectExceptionMessage("Article with id: some_key not found");
        $arguments->get('some_key');
    }
}