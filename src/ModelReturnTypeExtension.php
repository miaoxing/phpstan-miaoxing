<?php

namespace Miaoxing\PHPStan;

use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Wei\BaseModel;
use Wei\ModelTrait;

/**
 * Update model return type from 'ModelTrait' to real Model class
 *
 * Example:
 * AppModel::select()->desc('id')->findOrFail(1);
 *
 * Error:
 * Call to method select() on an unknown class Wei\ModelTrait.
 * Call to method findOrFail() on an unknown class Wei\ModelTrait.
 *
 * Reason:
 * BaseModel mixin ModelTrait, PHPStan parsed XxxModel to ModelTrait
 */
class ModelReturnTypeExtension implements DynamicMethodReturnTypeExtension, DynamicStaticMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return BaseModel::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $this->isServiceMethod($methodReflection);
    }

    #[\ReturnTypeWillChange]
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall       $methodCall,
        Scope            $scope
    ): ?Type
    {
        return $this->replaceReturnType($methodReflection, $methodCall, $scope);
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $this->isServiceMethod($methodReflection);
    }

    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall       $methodCall,
        Scope            $scope
    ): ?Type
    {
        return $this->replaceReturnType($methodReflection, $methodCall, $scope);
    }

    private function replaceReturnType(
        MethodReflection $methodReflection,
        CallLike         $methodCall,
        Scope            $scope
    )
    {
        $variant = ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $methodCall->getArgs(),
            $methodReflection->getVariants()
        );
        $returnType = $variant->getReturnType();

        if ($returnType instanceof UnionType) {
            $types = [];
            foreach ($returnType->getTypes() as $type) {
                $newType = $this->replaceType($methodCall, $scope, $type);
                if ($newType instanceof UnionType) {
                    $types = array_merge($types, $newType->getTypes());
                } else {
                    $types[] = $newType;
                }
            }
            return new UnionType($types);
        }

        return $this->replaceType($methodCall, $scope, $returnType);
    }

    /**
     * @param StaticCall|MethodCall $methodCall
     * @param Scope $scope
     * @param Type $type
     * @return Type
     */
    private function replaceType(CallLike $methodCall, Scope $scope, Type $type)
    {
        if ($this->isModelTrait($type)) {
            return $methodCall instanceof StaticCall ? $this->getModelType($methodCall, $scope) : $this->getVarType($methodCall, $scope);
        } else {
            return $type;
        }
    }

    private function getModelType(StaticCall $methodCall, Scope $scope)
    {
        if ($methodCall->class instanceof Name) {
            // ClassName::method
            return new ObjectType($methodCall->class->toCodeString());
        } else {
            // $expr::method
            return $scope->getType($methodCall->class->var);
        }
    }

    private function getVarType(MethodCall $methodCall, Scope $scope): Type
    {
        return $scope->getType($methodCall->var);
    }

    private function isModelTrait($returnType): bool
    {
        return $returnType instanceof ObjectType && $returnType->getClassName() === ModelTrait::class;
    }

    private function isServiceMethod(MethodReflection $methodReflection): bool
    {
        return strpos($methodReflection->getDocComment(), '* @svc');
    }
}
