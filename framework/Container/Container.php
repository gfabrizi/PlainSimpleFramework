<?php
namespace gfabrizi\PlainSimpleFramework\Container;

use ReflectionClass;
use ReflectionException;

/**
 * Simple Service Container
 *
 * Based on:
 * https://dev.to/azibom/make-your-own-service-container-php-51oe
 */
class Container implements ContainerInterface
{
    private array $services = [];

    /**
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        $item = $this->resolve($id);

        if (!($item instanceof ReflectionClass)) {
            return $item;
        }

        return $this->getInstance($item);
    }

    public function has($id): bool
    {
        try {
            $item = $this->resolve($id);
        } catch (NotFoundException $e) {
            return false;
        }

        if ($item instanceof ReflectionClass) {
            return $item->isInstantiable();
        }

        return isset($item);
    }

    public function set(string $key, $value): Container
    {
        $this->services[$key] = $value;
        return $this;
    }

    /**
     * @throws NotFoundException
     */
    private function resolve($id): ?ReflectionClass
    {
        try {
            $name = $id;
            if (isset($this->services[$id])) {
                $name = $this->services[$id];
                if (is_callable($name)) {
                    return $name();
                }
            }
            return (new ReflectionClass($name));
        } catch (ReflectionException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getInstance(ReflectionClass $item): ?object
    {
        $constructor = $item->getConstructor();
        if (is_null($constructor) || $constructor->getNumberOfRequiredParameters() === 0) {
            try {
                $instance = $item->newInstance();
            } catch (ReflectionException $e) {
                throw new ContainerException($e);
            }
        } else {
            $params = [];
            foreach ($constructor->getParameters() as $param) {
                if ($type = $param->getType()) {
                    $params[] = $this->get($type->getName());
                }
            }

            try {
                $instance = $item->newInstanceArgs($params);
            } catch (ReflectionException $e) {
                throw new ContainerException($e);
            }
        }

        return $instance;
    }
}
