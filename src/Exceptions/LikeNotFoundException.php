<?php

namespace a3330\pro_php_v2\src\Exceptions;

use Exception;

class LikeNotFoundException extends Exception
{
    protected $message = 'Like not found';
}