<?php

namespace a3330\pro_php_v2\src\Traits;

use a3330\pro_php_v2\src\Date\DateTime;

trait Updated
{
    private ?DateTime $updatedAt = null;

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt = null): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}