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
    private $pdo;

    /** @var DbConnectorInterface $dbConnector */
    private $dbConnector;

    private $config = [];
    private $dbConfig = [];

    private function __construct()
    {
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
        $this->dbConfig = $configContent['database'];

        if ($this->config['dbType'] === 'mysql') {
            $this->dbConnector = new MySqlConnector($this->dbConfig);
        } else {
            $this->dbConnector = new SqliteConnector($this->dbConfig);
        }
    }

    public function getPdo(): PDO
    {
        if (null === $this->pdo) {
            $this->pdo = $this->dbConnector->getPdo();
        }
        return $this->pdo;
    }

    public function get(string $param, string $default = null): string
    {
        return $this->config[$param] ?? $default;
    }
}