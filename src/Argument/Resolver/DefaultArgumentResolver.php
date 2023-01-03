<?php

namespace Creatortsv\SmartCallback\Argument\Resolver;

use Iterator;

class DefaultArgumentResolver implements ArgumentResolverInterface
{
    public function __invoke(string $name, ?mixed $resolvedValue = null, array|string ...$types): Iterator
    {
        
    }
}
