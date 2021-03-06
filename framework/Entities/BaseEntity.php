<?php
namespace gfabrizi\PlainSimpleFramework\Entities;

use JsonSerializable;

abstract class BaseEntity implements EntityInterface, JsonSerializable
{
    protected static $tableName;
    protected static $fields = [];
    protected static $join = [];
    protected $attributes = [];

    public function __construct()
    {
        foreach (self::getFields() as $field) {
            $this->attributes[$field] = ['value' => null];
        }
    }

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
        return array_keys(self::getFieldsWithAttributes($removeId));
    }

    /**
     * Returns an array with the db fields (columns) of the Entity plus the params for each field
     *
     * @param bool $removeId hides/shows the 'id' field from the results
     * @return array
     */
    public static function getFieldsWithAttributes(bool $removeId = false): array
    {
        if (true === $removeId){
            $fields = static::$fields;
            $key = array_search('id', $fields, true);

            if (false !== $key) {
                array_splice($fields, $key, 1);
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
     * @param $property string  Column name of the attribute to retrieve
     * @return mixed|null
     */
    public function get($property)
    {
        if (isset($this->attributes[$property])) {
            return $this->attributes[$property]['value'];
        }
        return null;
    }

    /**
     * Magic method to SET Entity properties
     *
     * @param $property string  Column name of the attribute to set
     * @param $value mixed  Value to set
     * @return EntityInterface
     */
    public function set($property, $value): EntityInterface
    {
        if (isset($this->attributes[$property])) {
            $field = $this::getFieldsWithAttributes()[$property];
            if(isset($field['type'])) {
                $value = $this->castAs($field['type'], $value ?? null);
            }
            $this->attributes[$property]['value'] = $value;
        }
        return $this;
    }

    private function castAs(string $type, $value)
    {
        if (null === $value) {
            return null;
        }

        switch ($type) {
            case 'int':
                return (int)$value;
                break;
            case 'float':
                return (float)$value;
                break;
            case 'string':
                return (string)$value;
                break;
            default:
                return $value;
        }
    }

    /**
     * Generic JSON Serialize method
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $data = array();

        foreach (static::getFields() as $field) {
            $data[$field] = $this->get($field);
        }

        return $data;
    }
}