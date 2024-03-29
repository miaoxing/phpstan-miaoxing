<?php

namespace Miaoxing\PHPStan\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;

class WeiMethodReflection implements MethodReflection
{
    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var MethodReflection
     */
    protected $methodReflection;

    /**
     * @var ClassReflection
     */
    protected $class;

    /**
     * @var array
     */
    protected $variants;

    /**
     * @var bool|null
     */
    protected $static;

    public function __construct(MethodReflection $methodReflection, string $methodName, array $variants, $static = null)
    {
        $this->methodReflection = $methodReflection;
        $this->methodName = $methodName;
        $this->variants = $variants;
        $this->static = $static;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->methodReflection->getDeclaringClass();
    }

    public function isStatic(): bool
    {
        return $this->static ?? $this->methodReflection->isStatic();
    }

    public function isPrivate(): bool
    {
        return $this->methodReflection->isPrivate();
    }

    public function isPublic(): bool
    {
        return $this->methodReflection->isPublic();
    }

    public function getDocComment(): ?string
    {
        return $this->methodReflection->getDocComment();
    }

    public function getName(): string
    {
        return $this->methodName;
    }

    public function getPrototype(): \PHPStan\Reflection\ClassMemberReflection
    {
        return $this->methodReflection->getPrototype();
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function isDeprecated(): \PHPStan\TrinaryLogic
    {
        return $this->methodReflection->isDeprecated();
    }

    public function getDeprecatedDescription(): ?string
    {
        return $this->methodReflection->getDeprecatedDescription();
    }

    public function isFinal(): \PHPStan\TrinaryLogic
    {
        return $this->methodReflection->isFinal();
    }

    public function isInternal(): \PHPStan\TrinaryLogic
    {
        return $this->methodReflection->isInternal();
    }

    public function getThrowType(): ?\PHPStan\Type\Type
    {
        return $this->methodReflection->getThrowType();
    }

    public function hasSideEffects(): \PHPStan\TrinaryLogic
    {
        return $this->methodReflection->hasSideEffects();
    }
}
