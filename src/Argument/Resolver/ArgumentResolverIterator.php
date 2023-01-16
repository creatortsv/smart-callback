<?php

namespace Creatortsv\SmartCallback\Argument\Resolver;

use ArrayIterator;
use Creatortsv\SmartCallback\Resolver\ResolverInterface;

/**
 * @method ResolverInterface current()
 * @method array<ResolverInterface> getArrayCopy()
 */
final class ArgumentResolverIterator extends ArrayIterator
{
    public function __construct(ResolverInterface ...$resolvers)
    {
        parent::__construct($resolvers);
    }
}
