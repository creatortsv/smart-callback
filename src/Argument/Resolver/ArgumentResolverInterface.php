<?php

namespace Creatortsv\SmartCallback\Argument\Resolver;

use Creatortsv\SmartCallback\Argument\Argument;
use Iterator;

/**
 * @template T
 */
interface ArgumentResolverInterface
{
    /**
     * @param array<T> $passed
     */
    public function __invoke(Argument $argument, array $passed, array $resolved): Iterator;
}
