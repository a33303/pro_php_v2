<?php

namespace a3330\pro_php_v2\src\Date;

use a3330\pro_php_v2\src\Enums\Date;
use DateTimeImmutable;

class DateTime extends DateTimeImmutable
{
    public function __toString(): string
    {
        return $this->format(Date::DATETIME_FORMAT->value);
    }

}