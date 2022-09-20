<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Models\Article;

interface ArticlesRepositoryInterface
{
    public function get(int $id): Article;
    public function findArticleByTitle(int $title): Article;
}