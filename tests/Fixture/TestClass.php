<?php

namespace MiaoxingTest\PHPStan\Fixture;

use function PHPStan\Testing\assertType;

#[\AllowDynamicProperties]
class TestClass
{
    use TestTrait;

    public function property()
    {
        assertType(TestMixin::class, $this->self);
    }

    public function method()
    {
        assertType('string', $this->callSelf());
    }
}
