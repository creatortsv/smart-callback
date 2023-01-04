<?php

namespace Creatortsv\SmartCallback\Argument;

use Creatortsv\SmartCallback\Helper\TypeExtractor;
use ReflectionParameter;

/**
 * @teplate TKey of array-key<string|int>
 * @template TypeValue of array<TKey, string>|string
 */
final class Argument
{
    /**
     * @var array<TKey, TypeValue> $types
     */
    public function __construct(
        public readonly string $name,
        public readonly int $position,
        public readonly bool $isOptional,
        public readonly bool $isVariadic,
        public readonly bool $allowsNull,
        public readonly array $types,
        private bool $resolved = false,
    ) {
    }

    public function hasType(): bool
    {
        return !empty($this->types);
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }

    public function setResolved(bool $state): void
    {
        $this->resolved = $state;
    }

    public static function create(ReflectionParameter $parameter): self
    {
        if (is_string($types = TypeExtractor::extract($parameter->getType()))) {
            $types = [$types];
        }

        return new static(
            $parameter->getName(),
            $parameter->getPosition(),
            $parameter->isOptional(),
            $parameter->isVariadic(),
            $parameter->allowsNull(),
            $types,
        );
    }
}
