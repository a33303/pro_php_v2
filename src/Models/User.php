<?php

namespace a3330\pro_php_v2\src\Models;

//include_once "src/Model.php";

use a3330\pro_php_v2\src\Date\DateTime;

use a3330\pro_php_v2\src\Traits\Active;
use a3330\pro_php_v2\src\Traits\Created;
use a3330\pro_php_v2\src\Traits\Deleted;
use a3330\pro_php_v2\src\Traits\Updated;
use a3330\pro_php_v2\src\Traits\Id;

class User
{
    use Id;
    use Active;
    use Created;
    use Updated;
    use Deleted;

    public function __construct(
        //int $id = null,
        private string $email,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $password = null,
        private ?User $author = null
    )
    {
        //parent::__construct($id);
        $this->createdAt = new DateTime();
    }

    public function __toString(){
        return
            $this->email. ' '.
            $this->firstName. ' '.
            $this->lastName .
            ' (на сайте с ' . $this->createdAt->format('Y-m-d') . ')';
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param string|null $password
     * @return User
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param User|null $author
     * @return User
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function hash(string $password): bool|string
    {
        return hash('sha256', $this->email . $password);
    }

    public function checkPassword(string $password): bool|string
    {
        return $this->hash($password) === $this->password;
    }



}