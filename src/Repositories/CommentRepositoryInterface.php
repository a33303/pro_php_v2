<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Models\Comment\Post;

interface CommentRepositoryInterface
{
    public function get(int $id): Post;
    public function findCommentByText(string $text): Post;
}