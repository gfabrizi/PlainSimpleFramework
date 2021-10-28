<?php
namespace gfabrizi\PlainSimpleFramework\Config;

use PDO;

final class MySqlConnector implements DbConnectorInterface
{
    private PDO $pdo;

    public function __construct(array $dbConfig)
    {
        $dsn = $this->getDsn(
            $dbConfig['host'],
            $this->dbConfig['port'] ?? 3306,
            $dbConfig['dbName']
        );
        $this->pdo = new PDO(
            $dsn,
            $dbConfig['username'],
            $dbConfig['password']
        );
    }

    private function getDsn(string $host, string $port, string $dbName): string
    {
        return sprintf('mysql:dbname=%s;host=%s;port=%s', $dbName, $host, $port);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}