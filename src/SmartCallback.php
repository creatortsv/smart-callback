<?php

namespace Creatortsv\SmartCallback;

use Closure;
use Creatortsv\SmartCallback\Argument\Resolver\ArgumentResolverInterface;
use Creatortsv\SmartCallback\Argument\Resolver\DefaultArgumentResolver;
use Creatortsv\SmartCallback\Argument\Resolver\InputArgumentResolver;
use ReflectionFunction;

/**
 * @template T
 */
class SmartCallback implements SmartCallbackIterface
{
    /**
     * @var callable
     */
    private $original;

    /**
     * @var array<ArgumentResolverInterface>
     */
    private array $resolvers;

    private string $name;

    private ReflectionFunction $reflection; 

    public function __construct(SmartCallbackIterface $smartCallback, callable $callback, ArgumentResolverInterface ...$argumentResolvers)
    {
        is_callable($callback, callable_name: $this->name);

        $this->original = $callback;
        $this->resolvers = [new InputArgumentResolver(), new DefaultArgumentResolver(), ...$argumentResolvers];
        $this->reflection = new ReflectionFunction(
            $callback instanceof Closure
                ? $callback
                : $callback(...),
        );        
    }

    public function getOriginal(): callable
    {
        return $this->original;
    }

    /**
     * @param T $mixed
     * @return T
     */
    public function __invoke(mixed ...$args): mixed
    {
        foreach ($this->reflection->getParameters() as $parameter) {
            if (!array_key_exists($parameter->getName(), $args) ||
                !array_key_exists($parameter->getPosition(), $args)) {
                // TODO: resolvers

                continue;
            }

            $args[$parameter->getName()] ??= $args[$parameter->getPosition()];
        }   
    }
}
