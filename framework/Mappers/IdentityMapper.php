<?php
namespace gfabrizi\PlainSimpleFramework\Mappers;

use Exception;
use gfabrizi\PlainSimpleFramework\Config\Configurator;
use gfabrizi\PlainSimpleFramework\Entities\EntityInterface;
use PDO;
use PDOStatement;

abstract class IdentityMapper
{
    protected PDO $pdo;
    protected string $tableName;

    protected ?PDOStatement $selectStmt = null;
    protected ?PDOStatement $selectAllStmt = null;
    protected ?PDOStatement $insertStmt = null;
    protected ?PDOStatement $updateStmt = null;
    protected ?PDOStatement $removeStmt = null;

    protected array $relations = [];

    public function __construct()
    {
        $this->pdo = Configurator::getInstance()->getPdo();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->tableName = $this->getTargetClass()::getTableName();
    }

    /**
     * Initialize the Statement Select applying any join relations
     *
     * @param string $tableName
     * @return string
     */
    protected function getSelectQuery(string $tableName): string
    {
        $selectAllQuery = $this->getSelectAllQuery($tableName);
        return sprintf('%s WHERE %s.id=?', $selectAllQuery, $this->getTargetClass()::getAlias());
    }

    /**
     * Initialize the Statement Select All applying any join relations
     *
     * @param string $tableName
     * @return string
     */
    protected function getSelectAllQuery(string $tableName): string
    {
        if ($relations = $this->getRelations()) {
            $selectFields = array();
            $joinClause = array();
            $selectFields[] = $this->getTargetClass()::getAlias() . '.*';

            /** @var RelationInterface $relation */
            foreach ($relations as $relation) {
                $foreignAlias = $relation->getTargetClass()::getAlias();
                $foreignTables = $relation->getTargetClass()::getTableName();

                foreach ($relation->getTargetClass()::getFields() as $field) {
                    $selectFields[] = $foreignAlias . '.' . $field . ' AS ' . $foreignAlias . '_' . $field;
                }
                $joinClause[] = 'LEFT JOIN ' . $foreignTables . ' AS ' . $foreignAlias .
                    ' ON ' . $this->getTargetClass()::getAlias() . '.' . $relation->getLocalColumn() .
                    ' = ' . $foreignAlias . '.' . $relation->getForeignColumn();
            }

            $query = sprintf(
                'SELECT %s FROM %s AS %s %s',
                implode(', ', $selectFields),
                $tableName,
                $this->getTargetClass()::getAlias(),
                implode(', ', $joinClause)
            );
        } else {
            $alias = $this->getTargetClass()::getAlias();
            $query = sprintf('SELECT %s.* FROM %s AS %s', $alias, $tableName, $alias);
        }

        return $query;
    }

    /**
     * Initialize the Statement Insert
     *
     * @param string $tableName
     * @return string
     */
    protected function getInsertQuery(string $tableName): string
    {
        $keys = $this->getTargetClass()::getFields(true);
        $columns = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));

        $query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $tableName, $columns, $placeholders);

        return $query;
    }

    /**
     * Initialize the Statement Update
     *
     * @param string $tableName
     * @param int $id
     * @return string
     */
    protected function getUpdateQuery(string $tableName, int $id): string
    {

        $keys = $this->getTargetClass()::getFields(true);
        $columns = implode(' = ?, ', $keys);
        $columns .= ' = ?';

        return sprintf('UPDATE %s SET %s WHERE id = %d', $tableName, $columns, $id);
    }

    /**
     * Initialize the Statement Delete
     *
     * @param string $tableName
     * @return string
     */
    protected function getRemoveQuery(string $tableName): string
    {
        return sprintf('DELETE FROM %s WHERE id=?;', $tableName);
    }

    /**
     * Search for an id in the table and returns the corresponding row
     *
     * @param int $id
     * @return EntityInterface|null
     */
    public function find(int $id): ?EntityInterface
    {
        if (!$this->getSelectStmt()) {
            $selectQuery = $this->getSelectQuery($this->tableName);
            $this->setSelectStmt($this->pdo->prepare($selectQuery));
        }

        $this->getSelectStmt()->execute([$id]);
        $row = $this->getSelectStmt()->fetch(PDO::FETCH_ASSOC);
        $this->getSelectStmt()->closeCursor();

        if (!is_array($row) || !isset($row['id'])) {
            return null;
        }

        return $this->hydrateEntity($row);
    }

    /**
     * Returns a Collection with all the table's rows
     *
     * @return Collection|null
     * @throws Exception
     */
    public function findAll(): ?Collection
    {
        if (!$this->getSelectAllStmt()) {
            $selectAllQuery = $this->getSelectAllQuery($this->tableName);
            $this->setSelectAllStmt($this->pdo->prepare($selectAllQuery));
        }

        $this->getSelectAllStmt()->execute();
        $rows = $this->getSelectAllStmt()->fetchAll(PDO::FETCH_ASSOC);
        $this->getSelectAllStmt()->closeCursor();

        if (!is_array($rows)) {
            return null;
        }

        return $this->getCollection($rows);
    }

    /**
     * Persists the Entity on db, delegating to child's doInsert() all the Entity's details
     *
     * @param EntityInterface $entity
     * @return int
     */
    public function insert(EntityInterface $entity): int
    {
        if (!$this->getInsertStmt()) {
            $insertQuery = $this->getInsertQuery($this->tableName);
            $this->setInsertStmt($this->pdo->prepare($insertQuery));
        }

        return $this->doInsert($entity);
    }

    /**
     * Persists the Entity on db, delegating to child's doInsert() all the Entity's details
     *
     * @param EntityInterface $entity
     * @return int
     */
    public function update(EntityInterface $entity): int
    {
        if (!$this->getUpdateStmt()) {
            $insertQuery = $this->getUpdateQuery($this->tableName, $entity->get('id'));
            $this->setUpdateStmt($this->pdo->prepare($insertQuery));
        }

        return $this->doUpdate($entity);
    }

    /**
     * Deletes Entity from db
     *
     * @param int $id
     */
    public function remove(int $id): void
    {
        if (!$this->getRemoveStmt()) {
            $removeQuery = $this->getRemoveQuery($this->tableName);
            $this->setRemoveStmt($this->pdo->prepare($removeQuery));
        }

        $this->getRemoveStmt()->execute([$id]);
    }

    /**
     * Adds a relation between Entities (join)
     *
     * @param RelationInterface $relation
     */
    protected function addRelation(RelationInterface $relation): void
    {
        $this->relations[] = $relation;
    }

    /**
     * Returns all the Entity's relations
     *
     * @return array
     */
    protected function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * Delegate to child's identity mapper the creation of the Entity from an array
     *
     * @param array $raw
     * @return EntityInterface
     */
    public function hydrateEntity(array $raw): EntityInterface
    {
        return $this->doHydrateEntity($raw);
    }

    public function getSelectStmt(): ?PDOStatement
    {
        return $this->selectStmt;
    }

    public function setSelectStmt(PDOStatement $stmt): IdentityMapper
    {
        $this->selectStmt = $stmt;
        return $this;
    }

    public function getSelectAllStmt(): ?PDOStatement
    {
        return $this->selectAllStmt;
    }

    public function setSelectAllStmt(PDOStatement $stmt): IdentityMapper
    {
        $this->selectAllStmt = $stmt;
        return $this;
    }

    public function getInsertStmt(): ?PDOStatement
    {
        return $this->insertStmt;
    }

    public function setInsertStmt(PDOStatement $stmt): IdentityMapper
    {
        $this->insertStmt = $stmt;
        return $this;
    }

    public function getUpdateStmt(): ?PDOStatement
    {
        return $this->updateStmt;
    }

    public function setUpdateStmt(PDOStatement $stmt): IdentityMapper
    {
        $this->updateStmt = $stmt;
        return $this;
    }

    public function getRemoveStmt(): ?PDOStatement
    {
        return $this->removeStmt;
    }

    public function setRemoveStmt(PDOStatement $stmt): IdentityMapper
    {
        $this->removeStmt = $stmt;
        return $this;
    }

    /**
     * @param array $raw
     * @return Collection
     * @throws Exception
     */
    public function getCollection(array $raw): Collection
    {
        return new Collection($raw, $this);
    }

    protected function doInsert(EntityInterface $entity): int
    {
        $param = [];
        foreach ($this->getTargetClass()::getFields(true) as $field) {
            $param[] = $entity->get($field);
        }
        $this->insertStmt->execute($param);
        $id = $this->pdo->lastInsertId();
        $entity->set('id', $id);
        return $id;
    }

    protected function doUpdate(EntityInterface $entity): int
    {
        $param = [];
        foreach ($this->getTargetClass()::getFields(true) as $field) {
            $param[] = $entity->get($field);
        }
        $this->updateStmt->execute($param);
        return $entity->get('id');
    }

    protected function doHydrateEntity(array $raw): EntityInterface
    {
        $targetClass = $this->getTargetClass();
        $entity = new $targetClass();

        foreach ($this->getTargetClass()::getFieldsWithAttributes() as $field => $params) {
            $entity->set($field, $raw[$field]);
        }

        return $entity;
    }

    abstract public function getTargetClass(): string;
}