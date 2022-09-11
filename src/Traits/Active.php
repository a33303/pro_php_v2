<?php

namespace a3330\pro_php_v2\src\Traits;

trait Active
{
    private bool $active = true;

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}