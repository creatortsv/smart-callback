<?php

namespace Creatortsv\SmartCallback\Argument\Resolver;

use Creatortsv\SmartCallback\Resolver\ResolverInterface;
use Iterator;

class DefaultResolver implements ResolverInterface
{
    public function __invoke(string $name, ?mixed $resolvedValue = null, array|string ...$types): Iterator
    {
        
    }
}
