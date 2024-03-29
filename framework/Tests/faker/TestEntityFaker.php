<?php
namespace gfabrizi\PlainSimpleFramework\Tests\faker;

use gfabrizi\PlainSimpleFramework\Config\Configurator;
use gfabrizi\PlainSimpleFramework\Tests\stubs\TestEntity;
use PDO;
use PDOException;

final class TestEntityFaker
{
    private static ?TestEntityFaker $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        $this->pdo = Configurator::getInstance()->getPdo();
        $this->doMigration();
    }

    private function doMigration(): void
    {
        try {
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //Error Handling

            $sql = 'CREATE TABLE IF NOT EXISTS `test_entity` (
                `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                `columnName1` TEXT(100) NOT NULL,
                `column_name2` TEXT(100) NOT NULL
            )';
            $this->pdo->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance(): TestEntityFaker
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function make(?int $id, $columnName1, $columnName2): TestEntity
    {
        $entity = new TestEntity();
        $entity->set('id', $id)
            ->set('columnName1', $columnName1)
            ->set('column_name2', $columnName2);

        return $entity;
    }

    public function reset(): void
    {
        try {
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); //Error Handling

            $sql = 'DELETE FROM `test_entity`';
            $this->pdo->exec($sql);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}