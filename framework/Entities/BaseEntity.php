<?php
namespace gfabrizi\PlainSimpleFramework\Entities;

use JsonSerializable;

abstract class BaseEntity implements EntityInterface, JsonSerializable
{
    protected static string $tableName;
    protected static array $fields = [];
    protected static array $join = [];
    protected array $attributes = [];

    public function __construct()
    {
        foreach (self::getFields() as $field) {
            $this->attributes[$field] = ['value' => null];
        }
    }

    public static function getTableName(): string
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
     * @return mixed
     */
    public function get($property): mixed
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

    /**
     * @param string $type
     * @param $value
     * @return float|int|string|null
     */
    private function castAs(string $type, $value): float|int|string|null
    {
        if (null === $value) {
            return null;
        }

        return match ($type) {
            'int' => (int)$value,
            'float' => (float)$value,
            'string' => (string)$value,
            default => $value,
        };
    }

    /**
     * Generic JSON Serialize method
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = array();

        foreach (static::getFields() as $field) {
            $data[$field] = $this->get($field);
        }

        return $data;
    }
}