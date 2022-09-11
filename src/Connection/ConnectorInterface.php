<?php

namespace a3330\pro_php_v2\src\Connection;

use PDO;

interface ConnectorInterface
{
    public static function getConnection(): PDO;
}