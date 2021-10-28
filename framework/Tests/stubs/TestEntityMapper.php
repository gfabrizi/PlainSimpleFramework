<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Mappers\IdentityMapper;

final class TestEntityMapper extends IdentityMapper
{
    public function getTargetClass(): string
    {
        return TestEntity::class;
    }
}