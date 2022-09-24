<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Models\Comment;

interface CommentRepositoryInterface
{
    public function get(int $id): Comment;
    public function findCommentByText(string $text): Comment;
}