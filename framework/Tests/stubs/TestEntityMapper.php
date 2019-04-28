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

    protected function doInsert(EntityInterface $entity): int
    {
        $this->insertStmt->execute([
            $entity->get('columnName1'),
            $entity->get('column_name2'),
        ]);
        $id = $this->pdo->lastInsertId();
        $entity->set('id', $id);
        return $id;
    }

    protected function doUpdate(EntityInterface $entity): int
    {
        $this->updateStmt->execute([
            $entity->get('columnName1'),
            $entity->get('column_name2'),
        ]);
        return $entity->get('id');
    }
}