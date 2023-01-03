<?php

namespace Creatortsv\SmartCallback;

/**
 * @template T
 */
interface SmartCallbackIterface
{
    /**
     * @param T ...$args
     * @return T
     */
    public function __invoke(mixed ...$args): mixed;
}
