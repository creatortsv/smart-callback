<?php

namespace Creatortsv\SmartCallback;

class CountableCallback implements SmartCallbackInterface
{
    private int $count = 0;

    public function __construct(private readonly SmartCallbackInterface $callback)
    {
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(mixed ...$args): mixed
    {
        $result = ($this->callback)(...$args);

        $this->count ++ ;

        return $result;
    }
}
