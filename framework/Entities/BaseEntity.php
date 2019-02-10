<?php
namespace gfabrizi\PlainSimpleFramework\Entities;

use JsonSerializable;

abstract class BaseEntity implements EntityInterface, JsonSerializable
{
    protected static $tableName;
    protected static $fields = [];
    protected static $join = [];

    public static function getTableName()
    {
        return static::$tableName;
    }

    /**
     * Returns an array with the db fields (columns) of the Entity
     *
     * @param bool $removeId hides/shows the 'id' field from the results
     * @return array
     */
    public static function getFields(bool $removeId = false): array
    {
        if (true === $removeId){
            $fields = static::$fields;

            if (false !== ($key = array_search('id', $fields))) {
                unset($fields[$key]);
            }

            return $fields;
        }
        return static::$fields;
    }

    /**
     * Returns an alias to be used in the sql queries (just the first letter of table name)
     *
     * @return string
     */
    public static function getAlias(): string
    {
        return strtolower(static::getTableName()[0]);
    }

    /**
     * Magic method to GET Entity properties
     *
     * @param $property
     * @return mixed|null
     */
    public function get($property)
    {
        if (in_array($property, static::getFields())) {
            return $this->{$property};
        }
        return null;
    }

    /**
     * Magic method to SET Entity properties
     *
     * @param $property
     * @param $value
     * @return EntityInterface
     */
    public function set($property, $value): EntityInterface
    {
        if (in_array($property, static::getFields())) {
            $this->{$property} = $value;
        }
        return $this;
    }
}