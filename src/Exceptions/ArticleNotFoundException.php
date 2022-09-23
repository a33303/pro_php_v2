<?php

namespace a3330\pro_php_v2\src\Exceptions;

use Exception;

class ArticleNotFoundException extends Exception
{
    protected $message = 'Article not found';
}