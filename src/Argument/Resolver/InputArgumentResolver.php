<?php

namespace Creatortsv\SmartCallback\Argument\Resolver;

use Creatortsv\SmartCallback\Argument\Argument;
use Iterator;

class InputArgumentResolver implements ArgumentResolverInterface
{
    public function __invoke(Argument $argument, array $passed): Iterator
    {
        if (!array_key_exists($argument->name, $passed) ||
            !array_key_exists($argument->position, $passed)) {
            return;
        }

        $val = $passed[$argument->name]
            ?? $passed[$argument->position]
            ?? null;

        $resolved = $val !== null || ($val === null && $argument->allowsNull);
        $argument->setResolved($resolved);

        yield $val;
    }
}
