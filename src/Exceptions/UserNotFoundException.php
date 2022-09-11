<?php

namespace a3330\pro_php_v2\src\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'User not found';
}