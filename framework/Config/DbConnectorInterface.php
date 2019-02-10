<?php
namespace gfabrizi\PlainSimpleFramework\Config;

use PDO;

interface DbConnectorInterface
{
    public function __construct(array $dbConfig);

    public function getPdo(): PDO;
}