<?php

namespace Creatortsv\SmartCallback\Support;

use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

/**
 * @template TKey of array-key<int>
 * @template TValue of array<TKey, string>|string
 */
class TypeExtractor
{
    /**
     * @return array<TKey, TValue>|string
     */
    public static function extract(?ReflectionType $type = null): array|string
    {
        if ($type instanceof ReflectionUnionType ||
            $type instanceof ReflectionIntersectionType) {
            return array_map(static::extract(...), $type->getTypes());
        }

        if ($type instanceof ReflectionNamedType) {
            return $type->getName();
        }

        return [];
    }
}
