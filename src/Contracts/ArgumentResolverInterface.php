<?php

namespace Creatortsv\CallableInstance\Contracts;

use Creatortsv\CallableInstance\Support\Argument;
use Iterator;

interface ArgumentResolverInterface
{
    public function resolve(Argument $argument, mixed $value = null): Iterator;

    public function supports(Argument $argument, mixed $value = null): bool;
}
