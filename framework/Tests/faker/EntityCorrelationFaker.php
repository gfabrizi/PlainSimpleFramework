<?php
namespace gfabrizi\PlainSimpleFramework\Tests\faker;

use gfabrizi\PlainSimpleFramework\Config\Configurator;
use gfabrizi\PlainSimpleFramework\Tests\stubs\EntityCorrelation;
use gfabrizi\PlainSimpleFramework\Tests\stubs\TestEntity;
use PDO;
use PDOException;

class EntityCorrelationFaker
{
    private static $instance;

    /**
     * @var PDO
     */
    private $pdo;

    private function __construct()
    {
        $this->pdo = Configurator::getInstance()->getPdo();
        $this->doMigration();
    }

    private function doMigration(): void
    {
        try {
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //Error Handling

            $sql = 'CREATE TABLE IF NOT EXISTS `entity_correlation` (
                `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `correlated_id` INTEGER NOT NULL,
                `username` TEXT(100) NOT NULL
            )';
            $this->pdo->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance(): EntityCorrelationFaker
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function make(?int $id, int $correlatedId, string $username): EntityCorrelation
    {
        return new EntityCorrelation($id, $correlatedId, $username);
    }

    public function reset(): void
    {
        try {
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //Error Handling

            $sql = 'DELETE FROM `entity_correlation`';
            $this->pdo->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}