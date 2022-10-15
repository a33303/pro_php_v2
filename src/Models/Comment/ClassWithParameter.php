<?php

namespace a3330\pro_php_v2\src\Models\Comment;

class ClassWithParameter
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}