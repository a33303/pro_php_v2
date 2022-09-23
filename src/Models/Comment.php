<?php

namespace a3330\pro_php_v2\src\Models;

//include_once "src/Model.php";

use a3330\pro_php_v2\src\Models\User;
use a3330\pro_php_v2\src\Models\Article;
use a3330\pro_php_v2\src\Traits\Id;

class Comment
{
    use Id;

    public function __construct(
        private User $author_id,
        private Article $article_id,
        private ?string $text
    )
    {
        //parent::__construct($id);
    }

    public function __toString(){
        return $this->text;
    }

    /**
     * @return User
     */
    public function getAuthorId(): User
    {
        return $this->author_id;
    }

    /**
     * @param User $author_id
     */
    public function setAuthorId(User $author_id): void
    {
        $this->author_id = $author_id;
    }

    /**
     * @return Article
     */
    public function getArticlesId(): Article
    {
        return $this->article_id;
    }

    /**
     * @param Article $article_id
     */
    public function setArticlesId(Article $article_id): void
    {
        $this->article_id = $article_id;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

}