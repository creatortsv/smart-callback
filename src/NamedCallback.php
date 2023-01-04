<?php

namespace Creatortsv\SmartCallback;

use Stringable;

class NamedCallback implements SmartCallbackInterface, Stringable
{
    public readonly string $name;

    public function __construct(private readonly SmartCallbackInterface $callback, ?string $name = null)
    {
        is_null($name) && is_callable($this->callback->getOriginal(), callable_name: $name);

        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(mixed ...$args): mixed
    {
        return ($this->callback)(...$args);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
