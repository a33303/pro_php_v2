<?php

namespace a3330\pro_php_v2\src\Handlers;

use a3330\pro_php_v2\src\Request\Request;
use a3330\pro_php_v2\src\Response\AbstractResponse;

interface LoginHandlerInterface
{
    public function handle(Request $request): AbstractResponse;
}