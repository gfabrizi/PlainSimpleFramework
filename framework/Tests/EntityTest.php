<?php
namespace gfabrizi\PlainSimpleFramework\Tests;

use gfabrizi\PlainSimpleFramework\Mappers\Collection;
use gfabrizi\PlainSimpleFramework\Tests\faker\EntityFaker;
use gfabrizi\PlainSimpleFramework\Tests\stubs\TestEntityMapper;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testItCanCreateAndRetrieve()
    {
        $mapper = new TestEntityMapper();
        $entity = EntityFaker::getInstance()->make(null, 'Lorem ipsum', 'dolor sit amet');
        $id = $mapper->insert($entity);
        $this->assertIsInt($id);

        $retrievedEntity = $mapper->find($id);
        $this->assertEquals('Lorem ipsum', $retrievedEntity->get('columnName1'));
        $this->assertEquals('dolor sit amet', $retrievedEntity->get('columnName2'));

    }

    public function testItCanUpdate()
    {
        $mapper = new TestEntityMapper();
        $entity = EntityFaker::getInstance()->make(null, 'Lorem ipsum', 'dolor sit amet');
        $id = $mapper->insert($entity);

        $retrievedEntity = $mapper->find($id);
        $retrievedEntity->set('columnName1', 'new value for col1');
        $retrievedEntity->set('columnName2', 'new value for col2');
        $mapper->update($retrievedEntity);

        $updatedEntity = $mapper->find($id);
        $this->assertEquals('new value for col1', $updatedEntity->get('columnName1'));
        $this->assertEquals('new value for col2', $updatedEntity->get('columnName2'));
    }

    public function testItCanDelete()
    {
        $mapper = new TestEntityMapper();
        $entity = EntityFaker::getInstance()->make(null, 'Lorem ipsum', 'dolor sit amet');
        $id = $mapper->insert($entity);

        $mapper->remove($id);

        $retrievedEntity = $mapper->find($id);
        $this->assertNull($retrievedEntity);
    }

    public function testItCanJsonEncodeItself()
    {
        $entity = EntityFaker::getInstance()->make(null, 'Lorem ipsum', 'dolor sit amet');

        $this->assertEquals('{"id":null,"columnName1":"Lorem ipsum","columnName2":"dolor sit amet"}', json_encode($entity));
    }

    public function testItCanFindAll()
    {
        $mapper = new TestEntityMapper();
        $this->assertCount(0, $mapper->findAll());

        $entity = EntityFaker::getInstance()->make(null, 'Lorem ipsum', 'dolor sit amet');
        $mapper->insert($entity);

        $entity = EntityFaker::getInstance()->make(null, 'consectetur adipiscing', 'elit.');
        $mapper->insert($entity);

        $entity = EntityFaker::getInstance()->make(null, 'Pellentesque quis', 'varius augue');
        $mapper->insert($entity);

        $retrievedEntities = $mapper->findAll();

        $this->assertInstanceOf(Collection::class, $retrievedEntities);
        $this->assertCount(3, $retrievedEntities);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        EntityFaker::getInstance()->reset();
    }
}