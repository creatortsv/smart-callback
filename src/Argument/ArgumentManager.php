<?php

namespace Creatortsv\SmartCallback\Argument;

use Creatortsv\CombinationIterator\CombinationIterator;
use Creatortsv\SmartCallback\Resolver\ResolverInterface;
use Creatortsv\SmartCallback\Resolver\ResolverIterator;
use Creatortsv\SmartCallback\Resolver\ShouldStopResolvingInterface;
use SplObjectStorage;

/**
 * @package creatortsv/smart-callback
 * @template T
 */
final class ArgumentManager
{
    /**
     * @var SplObjectStorage<Argument, T[]>
     */
    private SplObjectStorage $resolved;

    /**
     * @var CombinationIterator<int[], array{0:Argument, 1:ResolverInterface}>
     */
    private readonly CombinationIterator $iterator;

    public function __construct(
        private readonly ArgumentIterator $arguments,
        private readonly ResolverIterator $resolvers,
    ) {
        $this->resolved = new SplObjectStorage();
        $this->iterator = new CombinationIterator(
            $this->arguments,
            $this->resolvers,
        );
    }

    public function reset(): void
    {
        $this->resolved->removeAll($this->resolved);
        $this->iterator->rewind();
        $this->arguments->reset();
    }

    /**
     * @param T[] $context
     * @return T[]
     */
    public function resolve(array $context): array
    {
        $this->reset();

        $passed = [];

        array_walk($this->arguments, $fn = function (Argument $argument) use ($context, &$passed, &$fn): void {
            if (!array_key_exists($argument->name, $context) &&
                !array_key_exists($argument->position, $context)) {
                return;
            }

            $passed[$argument->position] = $context[$argument->name] ?? $context[$argument->position];

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

        foreach ($this->iterator as $iteration) {
            [
                $argument,
                $resolver,
            ] = $iteration;

            $this->resolved[$argument] = $resolver($argument, [], []);

            if ($argument->isResolved() !== true) {
                $resolver instanceof ShouldStopResolvingInterface && $argument->setResolved($resolver->stop());
            }
        }

        return iterator_to_array($this->resolved);
    }
}
