<?php

namespace a3330\pro_php_v2\src\Commands;

use a3330\pro_php_v2\src\Argument\Argument;

interface CreateLikeCommandInterface
{
    public function handle(Argument $argument): void;
}