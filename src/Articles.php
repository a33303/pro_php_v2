<?php

namespace a3330\pro_php_v2\src;

include_once "src/Model.php";

use a3330\pro_php_v2\src\User;

class Articles extends Model
{
    public function __construct(
        int $id = null,
        private User $author_id,
        private ?string $title,
        private ?string $description
    )
    {
        parent::__construct($id);
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
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


}