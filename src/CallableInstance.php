<?php

namespace Creatortsv\CallableInstance;

use Creatortsv\CallableInstance\Resolver\ArgumentResolverManager;
use Creatortsv\CallableInstance\Support\Argument;
use Iterator;

/**
 * @template T
 */
class CallableInstance
{
    public readonly string $name;

    /**
     * @var Iterator<string, Argument>
     */
    private readonly iterable $arguments;

    /**
     * @var callable
     */
    private $original;

    public function __construct(callable $callable, private readonly ArgumentResolverManager $argumentResolverManager = new ArgumentResolverManager())
    {
        is_callable($callable, callable_name: $name);

        $this->name = $name;
        $this->original = $callable;
        $this->arguments = Argument::iterator($callable);
    }

    public function getOriginal(): callable
    {
        return $this->original;
    }

    /**
     * @return Iterator<string, Argument>
     */
    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    public function getArgumentResolverManager(): ArgumentResolverManager
    {
        return $this->argumentResolverManager;
    }

    public function __invoke(mixed ...$arguments): mixed
    {
        return ($this->original)(...$this->resolveArgs($arguments));
    }

    /**
     * @param array<T> $arguments Passed arguments
     * @return Iterator<T>
     */
    private function resolveArgs(array $arguments): Iterator
    {
        $definedArgsIndex = 0;
        $shouldBeResolved = [];

        foreach ($this->arguments as $name => $argument) {
            $shouldBeResolved[$name] = [
                $argument,
            /** Get passed value or default */
                $arguments[$name] ??
                $arguments[$definedArgsIndex] ??
                $argument->getDefaultValue(),
            ];

            $definedArgsIndex ++ ;
        }

        yield from $this->argumentResolverManager->isEmpty()
            ? array_map(static fn (array $data): mixed => $data[1], $shouldBeResolved)
            : array_map($this->getResolvedArgument(...), $shouldBeResolved);
    }

    /**
     * @param array{0:Argument, 1:mixed} $argumentData
     */
    private function getResolvedArgument(array $argumentData): Iterator
    {
        yield from $this->argumentResolverManager->tryResolve(...$argumentData);
    }
}
