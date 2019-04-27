<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Entities\BaseEntity;

class EntityCorrelation extends BaseEntity
{
    protected static $tableName = 'entity_correlation';
    protected static $fields = ['id', 'correlated_id', 'username'];

    protected $id;
    protected $correlated;
    protected $username;

    public function __construct(?int $id, TestEntity $correlated, string $username)
    {
        $this->id = $id;
        $this->correlated = $correlated;
        $this->username = $username;
    }

    public function getCorrelated(): TestEntity
    {
        return $this->correlated;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->get('id'),
            'correlated' => $this->getCorrelated(),
            'username' => $this->get('username'),
        ];
    }

}