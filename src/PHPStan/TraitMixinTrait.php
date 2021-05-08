<?php

namespace Miaoxing\CodingStandards\PHPStan;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassReflection;

trait TraitMixinTrait
{
    /**
     * @var Broker
     */
    protected $broker;

    /** @var string[] */
    private $mixinExcludeClasses;

    /**
     * @param string[] $mixinExcludeClasses
     */
    public function __construct(array $mixinExcludeClasses)
    {
        $this->mixinExcludeClasses = $mixinExcludeClasses;
    }

    private function getTraits(ClassReflection $classReflection)
    {
        $traits = $this->getTraitClassesDeep($classReflection->getName());
        return \array_map(function (string $class): ClassReflection {
            return $this->broker->getClass($class);
        }, $traits);
    }

    private function getTraitClassesDeep(string $class)
    {
        $traits = class_uses($class);

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch));
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        }

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait), $traits);
        }

        return array_unique($traits);
    }

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }
}
