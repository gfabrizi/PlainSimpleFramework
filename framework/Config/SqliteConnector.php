<?php
namespace gfabrizi\PlainSimpleFramework\Config;

use PDO;

final class SqliteConnector implements DbConnectorInterface
{
    private PDO $pdo;

    public function __construct(array $dbConfig)
    {
        if ('memory' === $dbConfig['filename']) {
            $dsn = 'sqlite::memory:';
        } else {
            $dsn = 'sqlite:' . __DIR__ . '/../..' . $dbConfig['filename'];
        }

        $this->pdo = new PDO($dsn);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}