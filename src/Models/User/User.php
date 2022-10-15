<?php

namespace a3330\pro_php_v2\src\Models\User;

//include_once "src/Model.php";

use a3330\pro_php_v2\src\Date\DateTime;
use a3330\pro_php_v2\src\Traits\Active;
use a3330\pro_php_v2\src\Traits\Created;
use a3330\pro_php_v2\src\Traits\Deleted;
use a3330\pro_php_v2\src\Traits\Id;
use a3330\pro_php_v2\src\Traits\Updated;

class User
{
    use Id;
    use Active;
    use Created;
    use Updated;
    use Deleted;

    public function __construct(
        private string $email,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $password = null,
        private ?User $author = null
    )
    {
        $this->createdAt = new DateTime();
    }

    public function __toString(){
        return
            $this->email. ' '.
            $this->firstName. ' '.
            $this->lastName .
            ' (на сайте с ' . $this->createdAt->format('Y-m-d') . ')';
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

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