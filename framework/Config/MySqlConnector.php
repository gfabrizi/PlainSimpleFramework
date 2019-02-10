<?php
namespace gfabrizi\PlainSimpleFramework\Config;

use PDO;

class MySqlConnector implements DbConnectorInterface
{
    private $pdo;
    private $dbConfig;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    private function getDsn(string $host, string $port, string $dbName): string
    {
        return sprintf('mysql:dbname=%s;host=%s;port=%s', $dbName, $host, $port);
    }

    public function getPdo(): PDO
    {
        if (null === $this->pdo) {
            $dsn = $this->getDsn(
                $this->dbConfig['host'],
                $this->dbConfig['port'] ?? 3306,
                $this->dbConfig['dbName']
            );
            $this->pdo = new PDO(
                $dsn,
                $this->dbConfig['username'],
                $this->dbConfig['password']
            );
        }
        return $this->pdo;
    }

}