<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function get(int $id): User;
}