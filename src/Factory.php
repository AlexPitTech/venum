<?php

namespace PitTech\vEnum;

class Factory
{
    /**
     * @param class-string $class
     * @return Collection
     * @throws \ReflectionException
     * @throws OverflowException
     */
    public static function build(string $class): Collection
    {
        static $collections;
        return $collections[$class] ??= static::generate($class);
    }

    /**
     * @param class-string $class
     * @return collection
     * @throws \ReflectionException
     * @throws OverflowException
     */
    public static function generate(string $class): Collection
    {
        $collection = [];
        $rc = new \ReflectionClass($class);

        foreach($rc->getReflectionConstants(\ReflectionClassConstant::IS_PUBLIC) as $rcc) {
            if ($rcc->name === 'VALUES') {
                continue;
            }

            $rcAttributes = $rcc->getAttributes(vEnum::class);
            if (empty($rcAttributes)) {
                continue;
            }

            /** @var \ReflectionAttribute $rca */
            $rca = array_pop($rcAttributes);

            /** @var vEnum $vEnum */
            $vEnum = $rca->newInstance();

            $value = $rcc->getValue();
            if ($value instanceof \BackedEnum) {
                $vEnum->value = $value->value;
                $vEnum->name = $value->name;
            } else {
                $vEnum->value = $value;
                $vEnum->name = $rcc->name;
            }

            if (isset($collection[$vEnum->value])) {
                throw new OverflowException("vEnum case with value has been registered already", $vEnum->value);
            }

            $collection[$vEnum->value] = $vEnum;

        }

        return new Collection($collection, $class);
    }
}
