<?php

namespace Creatortsv\SmartCallback;

use Creatortsv\SmartCallback\Argument\ArgumentIterator;
use Creatortsv\SmartCallback\Context\ContextInterface;
use Creatortsv\SmartCallback\Resolver\ResolverIterator;
use ReflectionException;

/**
 * @template T
 */
final class SmartCallback implements SmartCallbackInterface
{
    /**
     * @var callable
     */
    private $original;

    private readonly ArgumentIterator $arguments;

    /**
     * @throws ReflectionException
     */
    public function __construct(
        callable $callback,
        private readonly ResolverIterator $resolvers,
        private readonly ContextInterface $context,
    ) {
        $this->original = $callback;
        $this->arguments = new ArgumentIterator($this->original);
    }

    public function getOriginal(): callable
    {
        return $this->original;
    }

    public function hasArguments(): bool
    {
        return (bool) $this->arguments->count();
    }

    /**
     * @inheritDoc
     */
    public function __invoke(mixed ...$args): mixed
    {
        return ($this->original)(...$args);
    }
}
