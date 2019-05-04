<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\BaseEntity;

class TestEntity extends BaseEntity
{
    protected static $tableName = 'test_entity';
    protected static $fields = [
        'id' => ['type' => 'int'],
        'columnName1' => ['type' => 'string'],
        'column_name2' => ['type' => 'string']
    ];
}