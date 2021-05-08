<?php

namespace Miaoxing\CodingStandards\PHPStan;

use Miaoxing\CodingStandards\PHPStan\Reflection\WeiPropertyReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\ObjectType;
use Wei\Wei;

/**
 * 允许通过 wei()->xxx 获取服务
 *
 * 对应错误：
 *  "#^Access to an undefined property Wei\\\\Wei\\:\\:\\$(.+?)\\.$#"
 */
class WeiPropertyExtension implements PropertiesClassReflectionExtension
{
    public function __construct()
    {
        $alieses = wei()->classMap->generate(['src', 'plugins/*/src'], '/Service/*.php', 'Service');
        wei()->setAliases($alieses);
    }

    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        return Wei::class === $classReflection->getName()
            && !property_exists(Wei::class, $propertyName)
            && \wei()->has($propertyName);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        $class = wei()->getClass($propertyName);
        return new WeiPropertyReflection(new ObjectType($class), $classReflection);
    }
}
