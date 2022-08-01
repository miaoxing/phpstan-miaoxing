<?php

namespace Miaoxing\PHPStan;

use Miaoxing\PHPStan\Reflection\WeiMethodReflection;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\Php\DummyParameter;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;

/**
 * 允许通过 V::xxx()->xxx() 调用
 *
 * 对应错误：
 * "Call to an undefined method Wei\V::xxx()."
 */
class ValidatorMethodsClassReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var Broker
     */
    protected $broker;

    public function __construct()
    {
        $alieses = \Wei\Wei::getContainer()->classMap->generate(['src', 'plugins/*/src'], '/Service/*.php', 'Service');
        \Wei\Wei::getContainer()->setAliases($alieses);
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return 'Wei\V' === $classReflection->getName()
            && !method_exists('Wei\V', $methodName)
            && \Wei\Wei::getContainer()->has('is' . ucfirst($methodName));
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        // 返回值使用当前类，即 Wei\V
        $returnType = new ObjectType($classReflection->getName());

        $class = \Wei\Wei::getContainer()->getClass('is' . ucfirst($methodName));
        $methodReflection = $this->broker->getClass($class)->getMethod('__invoke', new OutOfClassScope());
        $variants = $methodReflection->getVariants();
        $variant = $variants[0];

        // NOTE: $v 的方法有两种调用方式，暂时无法判断是哪种，使用假参数做兼容
        $parameter = new DummyParameter('p', new MixedType(), true, null, false, null);
        $parameters = [
            $parameter,
            $parameter,
            $parameter,
            $parameter,
        ];

        $variants[0] = new FunctionVariant(
            $variant->getTemplateTypeMap(),
            $variant->getResolvedTemplateTypeMap(),
            $parameters,
            $variant->isVariadic(),
            $returnType
        );

        return new WeiMethodReflection($methodReflection, $methodName, $variants, true);
    }

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }
}
