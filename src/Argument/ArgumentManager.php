<?php

namespace Creatortsv\SmartCallback\Argument;

use ArrayIterator;
use Closure;
use Countable;
use Creatortsv\SmartCallback\Argument\Resolver\ArgumentResolverInterface;
use Creatortsv\SmartCallback\Argument\Resolver\DefaultArgumentResolver;
use Creatortsv\SmartCallback\Argument\Resolver\InputArgumentResolver;
use InfiniteIterator;
use ReflectionFunction;

/**
 * @template T
 */
final class ArgumentManager implements Countable
{
    /**
     * @var array<array-key<int>, T>
     */
    private array $passed = [];

    /**
     * @var array<array-key<int>, T>
     */
    private array $resolved = [];

    public function count(): int
    {
        return count($this->arguments);
    }

    /**
     * @param array<array-key<int>, T> $input
     * @return array<array-key<int>, T>
     */
    public function resolveArguments(array $input): array
    {
        $this->reset();

        $passed = [];

        array_walk($this->arguments, $fn = function (Argument $argument) use ($input, &$passed, &$fn): void {
            if (!array_key_exists($argument->name, $input) &&
                !array_key_exists($argument->position, $input)) {
                return;
            }

            $passed[$argument->position] = $input[$argument->name] ?? $input[$argument->position];

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

        ksort($passed, SORT_NUMERIC);

        $resolvers = new InfiniteIterator(new ArrayIterator($this->resolvers));
        $arguments = new InfiniteIterator(new ArrayIterator($this->arguments));
        
        foreach ($this->arguments as $argument) {
            if ($argument->isResolved()) {
                break;
            }

            foreach ($this->resolvers as $resolver) {
                $resolver($argument, $passed, $this->resolved);
            }
        }

        return $this->resolved;
    }

    public static function create(callable $callback, ArgumentResolverInterface ...$resolvers): ArgumentManager
    {
        $reflection = new ReflectionFunction(
            function: $callback instanceof Closure
                ? $callback
                : $callback(...),
        );

        $arguments = array_map(Argument::create(...), $reflection->getParameters());

        return new ArgumentManager($arguments, [new InputArgumentResolver(), new DefaultArgumentResolver(), ...$resolvers]);
    }

    /**
     * @param array<Argument> $arguments
     * @param array<ArgumentResolverInterface> $resolvers
     */
    private function __construct(
        private readonly array $arguments,
        private readonly array $resolvers,
    ) {
    }

    /**
     * @param array<array-key<int>, T> $passed
     */
    private function setPassed(array $passed): void
    {

    }

    private function reset(): void
    {
        $this->passed = [];
        $this->resolved = [];

        array_walk($this->arguments, static fn (Argument $argument) => $argument->setResolved(false));

        reset($this->arguments);
        reset($this->resolvers);
    }
}
