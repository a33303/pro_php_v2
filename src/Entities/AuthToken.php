<?php

namespace a3330\pro_php_v2\src\Entities;

use a3330\pro_php_v2\src\Models\User\User;
use DateTimeInterface;

class AuthToken
{
    public function __construct(
        private string $token,
        private User $user,
        private DateTimeInterface $expiresAt
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }


    public function getUser(): User
    {
        return $this->user;
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}