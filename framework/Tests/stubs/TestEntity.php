<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\BaseEntity;

class TestEntity extends BaseEntity
{
    protected static $tableName = 'test_entity';
    protected static $fields = ['id', 'columnName1', 'column_name2'];

    public function __construct(?int $id, string $columnName1, string $columnName2)
    {
        parent::__construct();

        $this->set('id', $id);
        $this->set('columnName1', $columnName1);
        $this->set('column_name2', $columnName2);
    }
}