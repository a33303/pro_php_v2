<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Models\Like;

interface LikeRepositoryInterface
{
    public function get(int $id): Like;
    public function getByPostId(int $article_id): Like;
}