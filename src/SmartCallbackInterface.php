<?php

namespace Creatortsv\SmartCallback;

/**
 * @template T
 */
interface SmartCallbackInterface
{
    /**
     * @param T ...$args
     * @return T
     */
    public function __invoke(mixed ...$args): mixed;
}
