<?php

namespace a3330\pro_php_v2\src\Repositories;

use a3330\pro_php_v2\src\Connection\ConnectorInterface;
use PDO;

abstract class AbstractRepository
{
    protected PDO $connection;

    public function __construct(private ConnectorInterface $connector)
    {
        $this->connection = $this->connector->getConnection();
    }
}