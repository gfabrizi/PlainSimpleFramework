<?php
namespace gfabrizi\PlainSimpleFramework\Config;

use PDO;

/**
 * Class Configurator
 * Singleton class for managing base settings used throughout the app
 *
 * @package gfabrizi\PlainSimpleFramework\config
 */
class Configurator
{
    /** @var Configurator $instance */
    private static $instance;

    /** @var PDO $pdo */
    private PDO $pdo;

    private array $config = [];

    private function __construct()
    {
        // Singleton design pattern
    }

    public static function getInstance(): Configurator
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->setup();
        }
        return self::$instance;
    }

    private function setup(): void
    {
        if (isset($_ENV['APP_ENV']) && ('test' === $_ENV['APP_ENV'])) {
            $configFile = framework_path('/Config/config_test.ini');
        } else {
            $configFile = app_path('/Config/config.ini');
        }

        $configContent = parse_ini_file($configFile, true);
        $this->config = $configContent['configuration'];

        if ($this->config['dbType'] === 'mysql') {
            $dbConnector = new MySqlConnector($configContent['database']);
        } else {
            $dbConnector = new SqliteConnector($configContent['database']);
        }

        $this->pdo = $dbConnector->getPdo();
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function get(string $param, string $default = null): string
    {
        return $this->config[$param] ?? $default;
    }
}