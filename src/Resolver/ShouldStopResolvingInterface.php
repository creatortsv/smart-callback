<?php

namespace Creatortsv\SmartCallback\Resolver;

interface ShouldStopResolvingInterface
{
    public function stop(): bool;
}
