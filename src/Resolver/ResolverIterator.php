<?php

namespace Creatortsv\SmartCallback\Resolver;

use ArrayIterator;
use Creatortsv\SmartCallback\Argument\Resolver\ContextResolver;
use Creatortsv\SmartCallback\Argument\Resolver\DefaultResolver;

/**
 * @package creatortsv/smart-callback
 *
 * @template-extends ArrayIterator<int, ResolverInterface>
 *
 * @method ResolverInterface current()
 * @method array<ResolverInterface> getArrayCopy()
 */
final class ResolverIterator extends ArrayIterator
{
    public function __construct(ResolverInterface ...$resolvers)
    {
        parent::__construct([new ContextResolver(), new DefaultResolver(), ...$resolvers]);
    }
}
