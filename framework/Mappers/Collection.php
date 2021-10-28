<?php
namespace gfabrizi\PlainSimpleFramework\Mappers;

use Countable;
use gfabrizi\PlainSimpleFramework\Entities\EntityInterface;
use Iterator;
use RuntimeException;

/**
 * Class Collection
 * Entity aggregate
 *
 * @package gfabrizi\PlainSimpleFramework\Mappers
 */
class Collection implements Iterator, Countable
{
    protected ?IdentityMapper $mapper;
    protected int $total = 0;
    protected array $raw = [];

    private int $pointer = 0;
    private array $entities = [];

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

        $this->entities[$this->total] = $entity;
        $this->total++;
    }

    /**
     * Creates an Entity from the results row specified
     *
     * @param $num
     * @return mixed
     */
    public function get($num): mixed
    {
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

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->get($this->pointer);
    }

    public function key(): float|bool|int|string|null
    {
        return $this->pointer;
    }

    public function next(): void
    {
        $row = $this->get($this->pointer);

        if (null !== $row) {
            $this->pointer++;
        }
    }

    public function valid(): bool
    {
        return (null !== $this->current());
    }

    public function getTargetClass(): string
    {
        return $this->mapper->getTargetClass();
    }

    public function count(): int
    {
        return $this->total;
    }
}