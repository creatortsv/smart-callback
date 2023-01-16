<?php

namespace Creatortsv\SmartCallback\Argument;

use ArrayIterator;
use Closure;
use Creatortsv\SmartCallback\Support\TypeExtractor;
use FilterIterator;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;

/**
 * @package creatortsv/smart-callback
 *
 * @template-extends FilterIterator<int, Argument>
 *
 * @method Argument current()
 * @method array<Argument> getArrayCopy()
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

        $iterator = new ArrayIterator(array_map($this::createItem(...), $reflection->getParameters()));

        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        $argument = $this
            ->getInnerIterator()
            ->current();

        return !$argument instanceof Argument
            || !$argument->isResolved();
    }

    public function reset(): void
    {
        array_map(static fn (Argument $argument) => $argument->setResolved(false), iterator_to_array($this->getInnerIterator()));
    }

    private static function createItem(ReflectionParameter $parameter): Argument
    {
        if (is_string($types = TypeExtractor::extract($parameter->getType()))) {
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
}
