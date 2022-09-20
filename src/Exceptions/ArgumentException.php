<?php

namespace a3330\pro_php_v2\src\Exceptions;

use RuntimeException;

class ArgumentException extends RuntimeException
{
    protected $message = 'Argument not found';

}