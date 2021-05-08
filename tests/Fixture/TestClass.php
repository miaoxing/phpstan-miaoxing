<?php

namespace MiaoxingTest\PHPStan\Fixture;

class TestClass
{
    use TestTrait;

    public function property()
    {
        // Access to an undefined property PHPStanMiaoxingTest\Fixture\TestClass::$self.
        return $this->self;
    }

    public function method()
    {
        // Call to an undefined method PHPStanMiaoxingTest\Fixture\TestClass::callSelf().
        return $this->callSelf();
    }
}
