<?php
namespace gfabrizi\PlainSimpleFramework\Mappers;

/**
 * Class HasOne
 * Manages One-to-One relations between Entities
 *
 * @package gfabrizi\PlainSimpleFramework\Mappers
 */
class HasOne implements RelationInterface
{
    public function __construct(
        private string $targetClass,
        private string $localColumn,
        private string $foreignColumn
    ) {}

    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    public function getLocalColumn(): string
    {
        return $this->localColumn;
    }

    public function getForeignColumn(): string
    {
        return $this->foreignColumn;
    }
}