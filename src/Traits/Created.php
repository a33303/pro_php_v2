<?php

namespace a3330\pro_php_v2\src\Traits;

use a3330\pro_php_v2\src\Date\DateTime;

trait Created
{
    private DateTime $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}