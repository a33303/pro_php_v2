<?php

namespace a3330\pro_php_v2\src\Models;

use a3330\pro_php_v2\src\Traits\Id;

class Like
{
    use Id;

    public function __construct(
        private User $user_id,
        private Article $article_id
    )
    {
    }

    /**
     * @return User
     */
    public function getUserId(): User
    {
        return $this->user_id;
    }

    /**
     * @param User $user_id
     */
    public function setUserId(User $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return Article
     */
    public function getArticleId(): Article
    {
        return $this->article_id;
    }

    /**
     * @param Article $article_id
     */
    public function setArticleId(Article $article_id): void
    {
        $this->article_id = $article_id;
    }


}