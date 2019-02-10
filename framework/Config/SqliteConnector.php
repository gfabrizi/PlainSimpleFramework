<?php
namespace gfabrizi\PlainSimpleFramework\Config;

use PDO;

class SqliteConnector implements DbConnectorInterface
{
    private $pdo;
    private $dbConfig;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function getPdo(): PDO
    {
        if (null === $this->pdo) {
            $this->pdo = new PDO("sqlite:" . __DIR__ . "/../.." . $this->dbConfig['filename']);
        }
        return $this->pdo;
    }

}