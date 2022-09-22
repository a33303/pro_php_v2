<?php

namespace Test\Article;

use a3330\pro_php_v2\src\Exceptions\ArticleNotFoundException;
use a3330\pro_php_v2\src\Models\Article;
use a3330\pro_php_v2\src\Models\Comment;
use a3330\pro_php_v2\src\Repositories\ArticlesRepositoryInterface;

class DummyArticleRepository implements ArticlesRepositoryInterface
{

    public function get(int $id): Article
    {
        throw new ArticleNotFoundException('Article not found');
    }

    public function findArticleByTitle(int $title): Article
    {
        return new Article();
    }
}