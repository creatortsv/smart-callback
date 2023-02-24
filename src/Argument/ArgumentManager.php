<?php

namespace Creatortsv\SmartCallback\Argument;

use Creatortsv\CombinationIterator\CombinationIterator;
use Creatortsv\SmartCallback\Resolver\ResolverInterface;
use Creatortsv\SmartCallback\Resolver\ResolverIterator;

/**
 * @package creatortsv/smart-callback
 */
class ArgumentManager implements ArgumentManagerInterface
{
    private readonly ResolverIterator $resolvers;

    public function __construct(ResolverInterface ...$resolvers)
    {
        $this->resolvers = new ResolverIterator(...$resolvers);
    }

    public function resolve(ArgumentIterator $arguments, mixed ...$context): array
    {
        $incoming = [];
        $resolved = [];
        $iterator = new CombinationIterator($arguments, $this->resolvers);

        array_walk($arguments, $fn = function (Argument $argument) use ($context, &$incoming, &$fn): void {
            if (!array_key_exists($argument->name, $context) &&
                !array_key_exists($argument->position, $context)) {
                return;
            }

            $incoming[$argument->position] = $context[$argument->name] ?? $context[$argument->position];

            if ($argument->isVariadic) {
                $position = $argument->position + 1;
                $argument = new Argument(
                    $argument->name,
                    $position,
                    $argument->isOptional,
                    $argument->isVariadic,
                    $argument->allowsNull,
                    $argument->types,
                );

                $fn($argument);
            }
        });

        ksort($incoming, SORT_NUMERIC);

        foreach ($iterator as [$argument, $resolver]) {
            $resolved[$argument->position] = $resolver($argument, $incoming, $resolved);
        }

        return $resolved;
    }
}
