<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\BaseEntity;
use gfabrizi\PlainSimpleFramework\Entities\EntityInterface;

final class CorrelationEntity extends BaseEntity
{
    protected static string $tableName = 'entity_correlation';
    protected static array $fields = [
        'id' => ['type' => 'int'],
        'correlated_id' => [],
        'username' => []
    ];

    public function getCorrelated(): EntityInterface
    {
        $mapper = new TestEntityMapper();
        return $mapper->find($this->get('correlated_id'));
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->get('id'),
            'correlated' => $this->getCorrelated(),
            'username' => $this->get('username'),
        ];
    }
}