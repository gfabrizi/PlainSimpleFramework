<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\EntityInterface;
use gfabrizi\PlainSimpleFramework\Mappers\HasOne;
use gfabrizi\PlainSimpleFramework\Mappers\IdentityMapper;

class CorrelationEntityMapper extends IdentityMapper
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

    protected function doHydrateEntity(array $raw): EntityInterface
    {
        $id = isset($raw['id']) ? (int)$raw['id'] : null;

        $testEntityMapper = new TestEntityMapper();
        if (isset($raw['t_id'], $raw['t_columnName1'], $raw['t_column_name2'])) {
            $testEntity = new TestEntity($raw['t_id'], $raw['t_columnName1'], $raw['t_column_name2']);
            $correlatedId = $testEntityMapper->insert($testEntity);
        } else {
            $correlatedId = $raw['correlated_id'];
        }

        return new CorrelationEntity(
            $id,
            $correlatedId,
            $raw['username']
        );
    }
}