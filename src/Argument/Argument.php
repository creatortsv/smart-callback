<?php

namespace Creatortsv\SmartCallback\Argument;

use Creatortsv\SmartCallback\Support\TypeExtractor;
use ReflectionParameter;

/**
 * @template TKey of array-key<string|int>
 * @template TypeValue of array<TKey, string>|string
 */
final class Argument
{
    /**
     * @param array<TKey, TypeValue> $types
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
}
