<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Article;

interface ArticlesRepositoryInterface
{
    public function save(Article $article): void;
    public function get(int $id): Article;
}