<?php

namespace Creatortsv\CallableInstance\Resolver;

use Closure;
use Creatortsv\CallableInstance\Contracts\ArgumentResolverInterface;
use Creatortsv\CallableInstance\Support\Argument;
use Iterator;

final class ArgumentResolverManager
{
    /**
     * @var array<ArgumentResolverInterface>
     */
    private array $argumentResolvers;

    public function __construct(ArgumentResolverInterface ...$argumentResolvers)
    {
        $this->setResolvers(...$argumentResolvers);
    }

    public function setResolvers(ArgumentResolverInterface ...$argumentResolvers): void
    {
        $this->argumentResolvers = $argumentResolvers;
    }

    public function addResolvers(ArgumentResolverInterface ...$argumentResolvers): void
    {
        $this->argumentResolvers = [...$this->argumentResolvers, ...$argumentResolvers];
    }

    public function tryResolve(Argument $argument, mixed $value): Iterator
    {
        if ($this->isEmpty()) {
            yield null;
        } else {
            yield from array_map($this->resolver($argument, $value)(...), $this->argumentResolvers);
        }
    }

    public function isEmpty(): bool
    {
        return !count($this->argumentResolvers);
    }

    private function resolver(Argument $argument, mixed $value): Closure
    {
        return fn (ArgumentResolverInterface $resolver): Iterator => $resolver->supports($argument, $value) && yield from $resolver->resolve($argument, $value);
    }
}
