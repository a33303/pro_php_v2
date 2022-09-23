<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Models\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(string $text): Comment;
}