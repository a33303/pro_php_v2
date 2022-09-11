<?php

namespace a3330\pro_php_v2\src\Traits;

use a3330\pro_php_v2\src\Date\DateTime;

trait Deleted
{
    private ?DateTime $deletedAt = null;

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTime $deletedAt = null): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isDeleted(): bool
    {
        return !empty($this->deletedAt);
    }
}