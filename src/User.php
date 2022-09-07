<?php

namespace a3330\pro_php_v2\src;

include_once "src/Model.php";

class User extends Model
{
        public function __construct(
        int $id = null,
        private ?string $firstName,
        private ?string $lastName,
    )
    {
        parent::__construct($id);
    }

    public function __toString(){
        return $this->firstName . $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }


}