<?php
namespace gfabrizi\PlainSimpleFramework\Entities;

interface EntityInterface
{
    public static function getTableName();

    public static function getFields(): array;

    public static function getAlias(): string;

    public function get($property);

    public function set($property, $value): EntityInterface;
}