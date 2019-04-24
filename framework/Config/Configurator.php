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
    private static $instance;
    private $config;
    private $dbConfig;
    private $pdo;

    /** @var DbConnectorInterface $dbConnector */
    private $dbConnector;

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
        $dbConfig = [];
        $config = [];
        if ('test' === $_ENV['APP_ENV']) {
            $configFile = framework_path('/Config/config-test.php');
        } else {
            $configFile = app_path('/Config/config.php');
        }
        require $configFile;

        $this->config = $config;
        $this->dbConfig = $dbConfig;

        if ($config['dbType'] === 'mysql') {
            $this->dbConnector = new MySqlConnector($dbConfig);
        } else {
            $this->dbConnector = new SqliteConnector($dbConfig);
        }
    }


    public function getPdo(): PDO
    {
        if (null === $this->pdo) {
            $this->pdo = $this->dbConnector->getPdo();
        }
        return $this->pdo;
    }

    public function get(string $param, string $default = null)
    {
        return $this->config[$param] ?? $default;
    }
}