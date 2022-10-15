<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Models\User\User;

interface UserRepositoryInterface
{
    public function get(int $id): User;
    public function findUserByEmail(string $email): User;
}