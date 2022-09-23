<?php

namespace a3330\pro_php_v2\src\Decorator;

class ArgumentDecorator
{

    public function __construct(public string $argument, public string $value)
    {
    }
}