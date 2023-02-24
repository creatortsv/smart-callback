<?php

namespace Creatortsv\SmartCallback;

use Creatortsv\SmartCallback\Argument\ArgumentIterator;
use Creatortsv\SmartCallback\Argument\ArgumentManagerInterface;
use ReflectionException;

/**
 * @template TOriginal of callable
 */
final class SmartCallback
{
    /**
     * @var TOriginal
     */
    private $original;

    private readonly ArgumentIterator $argumentIterator;

    /**
     * @param TOriginal $callback
     * @throws ReflectionException
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(callable $callback, private readonly ArgumentManagerInterface $argumentManager)
    {
        $this->original = $callback;
        $this->argumentIterator = new ArgumentIterator($callback);
    }

    /**
     * @return TOriginal
     */
    public function getOriginal(): callable
    {
        return $this->original;
    }

    public function __invoke(mixed ...$args): mixed
    {
        $this->argumentIterator->reset();

        return ($this->original)(...$this->argumentManager->resolve($this->argumentIterator, ...$args));
    }
}
