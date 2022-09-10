<?php

namespace a3330\pro_php_v2\src;

include_once "src/Model.php";

use a3330\pro_php_v2\src\User;
use a3330\pro_php_v2\src\Articles;

class Comments extends Model
{

    public function __construct(
        int $id = null,
        private User $author_id,
        private Articles $articles_id,
        private ?string $text
    )
    {
        parent::__construct($id);
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
     * @return Articles
     */
    public function getArticlesId(): Articles
    {
        return $this->articles_id;
    }

    /**
     * @param Articles $articles_id
     */
    public function setArticlesId(Articles $articles_id): void
    {
        $this->articles_id = $articles_id;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

}