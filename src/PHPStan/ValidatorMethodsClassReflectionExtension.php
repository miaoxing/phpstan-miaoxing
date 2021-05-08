<?php

namespace Miaoxing\CodingStandards\PHPStan;

use Miaoxing\CodingStandards\PHPStan\Reflection\WeiMethodReflection;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Type\ObjectType;
use Wei\V;

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
        $alieses = wei()->classMap->generate(['src', 'plugins/*/src'], '/Service/*.php', 'Service');
        wei()->setAliases($alieses);
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return 'Wei\V' === $classReflection->getName()
            && !method_exists('Wei\V', $methodName)
            && \wei()->has('is' . ucfirst($methodName));
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        // 返回值使用当前类，即 Wei\V
        $returnType = new ObjectType($classReflection->getName());

        $class = wei()->getClass('is' . ucfirst($methodName));
        $methodReflection = $this->broker->getClass($class)->getMethod('__invoke', new OutOfClassScope());
        $variants = $methodReflection->getVariants();

        $variant = $variants[0];

        // 首个参数是 $input, 需要移除掉
        $parameters = $variant->getParameters();
        array_shift($parameters);

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
