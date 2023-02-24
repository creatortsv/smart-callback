<?php

namespace Creatortsv\SmartCallback\Argument;

use ArrayIterator;
use Closure;
use FilterIterator;
use ReflectionException;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;

/**
 * @package creatortsv/smart-callback
 *
 * @template-extends FilterIterator<int, Argument>
 *
 * @method Argument|null current()
 * @method array<int, Argument> getArrayCopy()
 */
final class ArgumentIterator extends FilterIterator
{
    /**
     * @throws ReflectionException
     */
    public function __construct(callable $callback)
    {
        $reflection = new ReflectionFunction(
            function: $callback instanceof Closure
                ? $callback
                : $callback(...),
        );

        parent::__construct(
            new ArrayIterator(array_map($this->createArgument(...), $reflection->getParameters())),
        );
    }

    public function accept(): bool
    {
        return !$this->getInnerIterator()->current()?->isResolved();
    }

    public function reset(): void
    {
        array_map($this->resetSingleArgument(...), iterator_to_array($this->getInnerIterator()));
    }

    private function createArgument(ReflectionParameter $parameter): Argument
    {
        if (is_string($types = $this->extractType($parameter->getType()))) {
            $types = [$types];
        }

        return new Argument(
            $parameter->getName(),
            $parameter->getPosition(),
            $parameter->isOptional(),
            $parameter->isVariadic(),
            $parameter->allowsNull(),
            $types,
        );
    }

    private function resetSingleArgument(Argument $argument): void
    {
        $argument->setResolved(false);
    }

    private function extractType(?ReflectionType $type = null): array|string
    {
        if ($type instanceof ReflectionUnionType ||
            $type instanceof ReflectionIntersectionType) {
            return array_map($this->extractType(...), $type->getTypes());
        }

        if ($type instanceof ReflectionNamedType) {
            return $type->getName();
        }

        return [];
    }
}
