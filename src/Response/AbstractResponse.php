<?php

namespace a3330\pro_php_v2\src\Response;

use JsonSerializable;

abstract class AbstractResponse implements JsonSerializable
{
    abstract protected function payload(): array;

    public function jsonSerialize(): mixed
    {
        return
        [
            'success' => static::SUCCESS,
            ...$this->payload()
        ];
    }
}