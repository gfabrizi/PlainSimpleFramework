<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Mappers\HasOne;
use gfabrizi\PlainSimpleFramework\Mappers\IdentityMapper;

final class CorrelationEntityMapper extends IdentityMapper
{
    public function __construct()
    {
        $this->addRelation(new HasOne(TestEntity::class, 'correlated_id', 'id'));

        parent::__construct();
    }

    public function getTargetClass(): string
    {
        return CorrelationEntity::class;
    }
}