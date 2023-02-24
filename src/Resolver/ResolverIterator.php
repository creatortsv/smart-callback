<?php

namespace Creatortsv\SmartCallback\Resolver;

use ArrayAccess;
use ArrayIterator;
use Iterator;

/**
 * @package creatortsv/smart-callback
 *
 * @template-implements Iterator<int, ResolverInterface>
 * @template-extends ArrayAccess<int, ResolverInterface>
 *
 * @method ResolverInterface|null current()
 * @method array<int, ResolverInterface> getArrayCopy()
 */
final class ResolverIterator extends ArrayIterator
{
    public function __construct(ResolverInterface ...$resolvers)
    {
        parent::__construct([new ContextResolver(), new DefaultResolver(), ...$resolvers]);
    }
}
