<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\BaseEntity;

class TestEntity extends BaseEntity
{
    protected static $tableName = 'test_entity';
    protected static $fields = ['id', 'columnName1', 'columnName2'];

    protected $id;
    protected $columnName1;
    protected $columnName2;

    public function __construct(?int $id, string $columnName1, string $columnName2)
    {
        $this->id = $id;
        $this->columnName1 = $columnName1;
        $this->columnName2 = $columnName2;
    }
}