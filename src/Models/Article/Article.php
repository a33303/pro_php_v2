<?php

namespace a3330\pro_php_v2\src\Models\Article;

use a3330\pro_php_v2\src\Models\User\User;
use a3330\pro_php_v2\src\Traits\Id;

class Article
{
    use Id;

    public function __construct(
        private User $author,
        private string $title,
        private string $description
    )
    {
    }

    public function __toString(){
         return $this->title . $this->description;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

}