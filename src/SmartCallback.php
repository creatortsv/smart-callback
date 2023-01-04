<?php

namespace Creatortsv\SmartCallback;

use Creatortsv\SmartCallback\Argument\ArgumentManager;
use Creatortsv\SmartCallback\Argument\Resolver\ArgumentResolverInterface;
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

    private readonly ArgumentManager $argumentManager;

    /**
     * @throws ReflectionException
     */
    public function __construct(callable $callback, ArgumentResolverInterface ...$argumentResolvers)
    {
        $this->original = $callback;
        $this->argumentManager = ArgumentManager::create($callback, ...$argumentResolvers);
    }

    public function getOriginal(): callable
    {
        return $this->original;
    }

    public function hasArguments(): bool
    {
        return (bool) $this->argumentManager->count();
    }

    /**
     * @inheritDoc
     */
    public function __invoke(mixed ...$args): mixed
    {
        $args = $this->argumentManager->resolveArguments(input: $args);

        return ($this->original)(...$args);
    }
}
