<?php

namespace Creatortsv\CallableInstance\Tests;

use Creatortsv\CallableInstance\CallableInstance;
use PHPUnit\Framework\TestCase;

class CallableInstanceTest extends TestCase
{
    public function testInvoke(): void
    {
        $callable = fn (): bool => true;
        $instance = new CallableInstance($callable);

        $this->assertTrue($instance());
    }
}
