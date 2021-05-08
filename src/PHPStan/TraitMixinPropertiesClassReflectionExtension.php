<?php

namespace Miaoxing\CodingStandards\PHPStan;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\TypeUtils;

/**
 * Allow using `@mixin` with traits
 *
 * @link https://github.com/phpstan/phpstan/issues/3295
 */
class TraitMixinPropertiesClassReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    use TraitMixinTrait;

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        return null !== $this->findProperty($classReflection, $propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $property = $this->findProperty($classReflection, $propertyName);
        if (null === $property) {
            throw new ShouldNotHappenException();
        }
        return $property;
    }

    private function findProperty(ClassReflection $classReflection, string $propertyName): ?PropertyReflection
    {
        $traits = $this->getTraits($classReflection);

        foreach ($traits as $trait) {
            $mixinTypes = $trait->getResolvedMixinTypes();

            foreach ($mixinTypes as $type) {
                if (\count(\array_intersect(TypeUtils::getDirectClassNames($type), $this->mixinExcludeClasses)) > 0) {
                    continue;
                }
                if (!$type->hasProperty($propertyName)->yes()) {
                    continue;
                }
                return $type->getProperty($propertyName, new OutOfClassScope());
            }
        }

        foreach ($classReflection->getParents() as $parentClass) {
            $property = $this->findProperty($parentClass, $propertyName);
            if (null === $property) {
                continue;
            }
            return $property;
        }
        return null;
    }
}
