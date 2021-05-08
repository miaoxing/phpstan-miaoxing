<?php

namespace MiaoxingTest\PHPStan;

use Miaoxing\PHPStan\TraitMixinPropertiesClassReflectionExtension;
use PHPStan\Rules\Properties\AccessPropertiesRule;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

class TraitMixinPropertiesClassReflectionExtensionTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $broker = $this->createReflectionProvider();

        $this->getClassReflectionExtensionRegistryProvider()->addPropertiesClassReflectionExtension(
            new TraitMixinPropertiesClassReflectionExtension([])
        );

        return new AccessPropertiesRule($broker, new RuleLevelHelper($broker, true, true, true, false), true);
    }

    public function testRule()
    {
        $this->analyse([__DIR__ . '/Fixture/TestClass.php'], []);
    }
}
