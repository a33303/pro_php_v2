<?php

namespace a3330\pro_php_v2\src\Models;

//include_once "src/Model.php";

use a3330\pro_php_v2\src\Models\User;
use a3330\pro_php_v2\src\Traits\Id;

class Article
{
    use Id;

    public function __construct(
        private User $author_id,
        private string $title,
        private ?string $description
    )
    {
        //parent::__construct($id);
    }

    public function __toString(){
         return $this->title . $this->description;
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}