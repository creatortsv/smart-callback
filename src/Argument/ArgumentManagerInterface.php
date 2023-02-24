<?php

namespace Creatortsv\SmartCallback\Argument;

interface ArgumentManagerInterface
{
    public function resolve(ArgumentIterator $arguments, mixed ...$context): array;
}
