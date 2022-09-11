<?php

namespace a3330\pro_php_v2\src\Connection;

use PDO;

class SqLiteConnector implements ConnectorInterface
{
    public static function getConnection(): PDO
    {
        return new PDO(databaseConfig()['sqlite']['DATABASE_URL']);
    }
}