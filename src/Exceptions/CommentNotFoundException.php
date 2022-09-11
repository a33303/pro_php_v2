<?php

namespace a3330\pro_php_v2\src\Exceptions;

use Exception;

class CommentNotFoundException extends Exception
{
    protected $message = 'Comment not found';
}