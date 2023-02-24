<?php

namespace Creatortsv\SmartCallback\Resolver;

use Creatortsv\SmartCallback\Argument\Argument;
use Iterator;

class ContextResolver implements ResolverInterface
{
    public function __invoke(Argument $argument, array $passed, array $resolved): Iterator
    {
        if (!array_key_exists($argument->name, $passed) ||
            !array_key_exists($argument->position, $passed)) {
            return;
        }

        $val = $passed[$argument->name]
            ?? $passed[$argument->position]
            ?? null;

        $resolved = $val !== null || $argument->allowsNull;
        $argument->setResolved($resolved);

        yield $val;
    }
}
