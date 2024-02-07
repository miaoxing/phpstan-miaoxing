<?php

namespace MiaoxingTest\PHPStan;

use PHPStan\Testing\TypeInferenceTestCase;

class TraitMixinClassReflectionExtensionTest extends TypeInferenceTestCase
{
    public static function dataFileAsserts(): iterable
    {
        yield from static::gatherAssertTypes(__DIR__ . '/Fixture/TestClass.php');
    }

    /**
     * @dataProvider dataFileAsserts
     * @param mixed ...$args
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
        ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/data/extension.neon',
        ];
    }
}
