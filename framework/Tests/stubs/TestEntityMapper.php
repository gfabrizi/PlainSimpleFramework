<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\EntityInterface;
use gfabrizi\PlainSimpleFramework\Mappers\IdentityMapper;

class TestEntityMapper extends IdentityMapper
{
    public function getTargetClass(): string
    {
        return TestEntity::class;
    }

    protected function doHydrateEntity(array $raw): EntityInterface
    {
        $id = isset($raw['id']) ? (int)$raw['id'] : null;

        return new TestEntity(
            $id,
            $raw['columnName1'],
            $raw['column_name2']
        );
    }
}