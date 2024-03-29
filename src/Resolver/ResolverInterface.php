<?php

namespace Creatortsv\SmartCallback\Resolver;

use Creatortsv\SmartCallback\Argument\Argument;

/**
 * @template T
 */
interface ResolverInterface
{
    public function __invoke(Argument $argument, array $context, array $resolved): void;
}
