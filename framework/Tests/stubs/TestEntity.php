<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\BaseEntity;

final class TestEntity extends BaseEntity
{
    protected static string $tableName = 'test_entity';
    protected static array $fields = [
        'id' => ['type' => 'int'],
        'columnName1' => ['type' => 'string'],
        'column_name2' => ['type' => 'string']
    ];
}