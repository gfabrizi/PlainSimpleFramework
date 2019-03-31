<?php
namespace gfabrizi\PlainSimpleFramework\Mappers;

use gfabrizi\PlainSimpleFramework\Entities\EntityInterface;
use Iterator;
use RuntimeException;

/**
 * Class Collection
 * Entity aggregate
 *
 * @package gfabrizi\PlainSimpleFramework\Mappers
 */
abstract class Collection implements Iterator
{
    protected $mapper;
    protected $total = 0;
    protected $raw = [];

    private $pointer = 0;
    private $entities = [];

    /**
     * Collection constructor.
     * @param array $raw
     * @param IdentityMapper|null $mapper
     * @throws RuntimeException
     */
    public function __construct(array $raw = [], IdentityMapper $mapper = null)
    {
        $this->raw = $raw;
        $this->total = count($raw);

        if (null === $mapper && count($raw)) {
            throw new RuntimeException('A mapper is needed to generate an entity');
        }

        $this->mapper = $mapper;
    }

    /**
     * Add an Entity to the Collection
     *
     * @param EntityInterface $entity
     * @throws RuntimeException
     */
    public function add(EntityInterface $entity): void
    {
        $class = $this->getTargetClass();

        if (!$entity instanceof $class) {
            throw new RuntimeException('This is a collection of type ' . $class);
        }

        $this->notifyAccess();
        $this->entities[$this->total] = $entity;
        $this->total++;
    }

    protected function notifyAccess()
    {
    }

    /**
     * Creates an Entity from the results row specified
     *
     * @param $num
     * @return mixed|null
     */
    private function getRow($num)
    {
        $this->notifyAccess();

        if ($num >= $this->total || $num < 0) {
            return null;
        }

        if (isset($this->entities[$num])) {
            return $this->entities[$num];
        }

        if (isset($this->raw[$num])) {
            $this->entities[$num] = $this->mapper->hydrateEntity($this->raw[$num]);
            return $this->entities[$num];
        }

        return null;
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);

        if (null !== $row) {
            $this->pointer++;
        }
    }

    public function valid()
    {
        return (null !== $this->current());
    }

    abstract public function getTargetClass(): string;
}