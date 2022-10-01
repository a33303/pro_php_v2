<?php

namespace a3330\pro_php_v2\src\Connection;

use PDO;

class SqLiteConnector implements ConnectorInterface
{
    private static PDO $pdo;

    public function __construct(string $dsn)
    {
        self::$pdo = new PDO($dsn);
    }

    public static function getConnection(): PDO
    {
        return self::$pdo;
    }
}