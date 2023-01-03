<?php

namespace Creatortsv\SmartCallback\Argument\Resolver;

use Iterator;

interface ArgumentResolverInterface
{
    public function __invoke(string $name, mixed $resolvedValue = null, array|string ...$types): Iterator;
}
