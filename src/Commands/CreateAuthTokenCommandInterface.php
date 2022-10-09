<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Entities\AuthToken;

interface CreateAuthTokenCommandInterface
{
    public function handle(AuthToken $authToken): void;
}