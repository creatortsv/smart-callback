<?php

namespace Creatortsv\CallableInstance\Support;

use Generator;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

/**
 * @template T
 */
final class Argument
{
    /**
     * @param array<string> $types
     */
    public function __construct(
        public readonly string $name,
        public readonly array $types,
        public readonly bool $isOptional,
        public readonly bool $isVariadic,
        public readonly bool $isPromoted,
        public readonly bool $isIntersection,
        public readonly bool $isUnion,
        protected mixed $defaultValue = null,
    ) {
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $value = null): static
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * @return Generator<static>
     */
    public static function iterator(callable $callable): Generator
    {
        $reflect = new ReflectionFunction($callable(...));

        yield from array_merge(...array_map(static::map(...), $reflect->getParameters()));
    }

    /**
     * @return array<string, static>
     */
    private static function map(ReflectionParameter $parameter): array
    {
        $name = $parameter->getName();
        $type = $parameter->getType();

        $argument = new static(
            $name,
            types: static::mapTypesName($type),
            isOptional: $parameter->isOptional(),
            isVariadic: $parameter->isVariadic(),
            isPromoted: $parameter->isPromoted(),
            isIntersection: $type instanceof ReflectionIntersectionType,
            isUnion: $type instanceof ReflectionUnionType,
        );

        $argument->isOptional && $argument->setDefaultValue($parameter->getDefaultValue());
        
        return [$name => $argument];
    }

    /**
     * @return array<string|class-string<T>>
     */
    private static function mapTypesName(ReflectionNamedType | ReflectionIntersectionType | ReflectionUnionType $type): array
    {
        return $type instanceof ReflectionNamedType
            ? [$type->getName()]
            : array_map(static::mapTypesName(...), $type->getTypes());
    }
}
