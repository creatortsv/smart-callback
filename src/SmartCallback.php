<?php

namespace Creatortsv\SmartCallback;

use Closure;
use Creatortsv\SmartCallback\Argument\Resolver\ArgumentResolverInterface;
use Creatortsv\SmartCallback\Argument\Resolver\DefaultArgumentResolver;
use Creatortsv\SmartCallback\Argument\Resolver\InputArgumentResolver;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;

/**
 * @template T
 */
final class SmartCallback implements SmartCallbackInterface
{
    /**
     * @var callable
     */
    private $original;

    /**
     * @var array<ArgumentResolverInterface>
     */
    private readonly array $resolvers;

    /**
     * @var array<ReflectionParameter>
     */
    private readonly array $parameters;

    /**
     * @throws ReflectionException
     */
    public function __construct(callable $callback, ArgumentResolverInterface ...$argumentResolvers)
    {
        $this->original = $callback;
        $this->resolvers = [
            new InputArgumentResolver(),
            new DefaultArgumentResolver(),
            ...$argumentResolvers,
        ];

        $reflection = new ReflectionFunction(
            function: $callback instanceof Closure
                ? $callback
                : $callback(...),
        );

        $this->parameters = $reflection->getParameters();
    }

    public function getOriginal(): callable
    {
        return $this->original;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(mixed ...$args): mixed
    {
        foreach ($this->parameters as $parameter) {
            if (!array_key_exists($parameter->getName(), $args) ||
                !array_key_exists($parameter->getPosition(), $args)) {
                // TODO: resolvers

                continue;
            }

            $args[$parameter->getName()] ??= $args[$parameter->getPosition()];
        }

        reset($this->resolvers);

        return ($this->original)(...$args);
    }
}
