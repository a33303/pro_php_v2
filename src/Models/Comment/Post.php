<?php

namespace a3330\pro_php_v2\src\Models\Comment;

use a3330\pro_php_v2\src\Models\Article\Article;
use a3330\pro_php_v2\src\Models\User\User;
use a3330\pro_php_v2\src\Traits\Id;

class Post
{
    use Id;

    public function __construct(
        private User $author,
        private Article $article_id,
        private ?string $text
    )
    {
    }

    public function __toString(){
        return $this->author . ' пишет: ' . $this->text;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getArticlesId(): Article
    {
        return $this->article_id;
    }

    public function setArticlesId(Article $article_id): void
    {
        $this->article_id = $article_id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

}