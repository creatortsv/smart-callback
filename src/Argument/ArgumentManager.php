<?php

namespace Creatortsv\SmartCallback\Argument;

use Closure;
use Countable;
use Creatortsv\SmartCallback\Argument\Resolver\ArgumentResolverInterface;
use Creatortsv\SmartCallback\Argument\Resolver\DefaultArgumentResolver;
use Creatortsv\SmartCallback\Argument\Resolver\InputArgumentResolver;
use ReflectionFunction;

/**
 * @template T
 */
final class ArgumentManager implements Countable
{
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

        foreach ($this->arguments as $argument) {
            foreach ($this->resolvers as $resolver) {
                if ($argument->isResolved()) {
                    break;
                }

                $resolver($argument, $input, $this->resolved);
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

    private function reset(): void
    {
        $this->resolved = [];

        array_walk($this->arguments, static fn (Argument $argument) => $argument->setResolved(false));

        reset($this->arguments);
        reset($this->resolvers);
    }
}
