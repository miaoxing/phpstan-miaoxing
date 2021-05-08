<?php

namespace MiaoxingTest\PHPStan;

use Miaoxing\PHPStan\TraitMixinMethodsClassReflectionExtension;
use PHPStan\Php\PhpVersion;
use PHPStan\Rules\FunctionCallParametersCheck;
use PHPStan\Rules\Methods\CallMethodsRule;
use PHPStan\Rules\NullsafeCheck;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

class TraitMixinMethodsClassReflectionExtensionTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $broker = $this->createReflectionProvider();

        $this->getClassReflectionExtensionRegistryProvider()->addMethodsClassReflectionExtension(
            new TraitMixinMethodsClassReflectionExtension([])
        );

        $ruleLevelHelper = new RuleLevelHelper($broker, true, true, true, true);
        return new CallMethodsRule(
            $broker,
            new FunctionCallParametersCheck(
                $ruleLevelHelper,
                new NullsafeCheck(),
                new PhpVersion(\PHP_VERSION_ID),
                true,
                true,
                true,
                true
            ),
            $ruleLevelHelper,
            true,
            true
        );
    }

    public function testRule()
    {
        $this->analyse([__DIR__ . '/Fixture/TestClass.php'], []);
    }
}
