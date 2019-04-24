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
            if ('memory' === $this->dbConfig['filename']) {
                $dsn = 'sqlite::memory:';
            } else {
                $dsn = 'sqlite:' . __DIR__ . '/../..' . $this->dbConfig['filename'];
            }

            $this->pdo = new PDO($dsn);
        }
        return $this->pdo;
    }

}