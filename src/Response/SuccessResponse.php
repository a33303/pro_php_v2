<?php

namespace a3330\pro_php_v2\src\Response;

class SuccessResponse extends AbstractResponse
{
    protected const SUCCESS = true;

    public function __construct(private readonly array $data)
    {
    }

    protected function payload(): array
    {
        return ['data' => $this->data];
    }
}