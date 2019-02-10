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
    private $targetClass;
    private $localColumn;
    private $foreignColumn;

    public function __construct(string $className, string $localColumn, string $foreignColumn)
    {
        $this->targetClass = $className;
        $this->localColumn = $localColumn;
        $this->foreignColumn = $foreignColumn;
    }

    public function getTargetClass()
    {
        return $this->targetClass;
    }

    public function getLocalColumn()
    {
        return $this->localColumn;
    }

    public function getForeignColumn()
    {
        return $this->foreignColumn;
    }
}