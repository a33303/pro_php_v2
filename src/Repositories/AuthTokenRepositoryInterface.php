<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Entities\AuthToken;

interface AuthTokenRepositoryInterface
{
    public function getToken(string $token): AuthToken;
}