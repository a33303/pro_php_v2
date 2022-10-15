<?php

namespace a3330\pro_php_v2\src\Authentification;

use a3330\pro_php_v2\src\Models\User;
use a3330\pro_php_v2\src\Request\Request;

interface AuthentificationInterface
{
    public function user(Request $request): User;
}