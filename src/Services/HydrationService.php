<?php

namespace NitsujCodes\PDFDataTable\Services;

use NitsujCodes\PDFDataTable\DTO\Attributes\DefaultValue;
use NitsujCodes\PDFDataTable\DTO\Nullable;
use ReflectionClass;
use Exception;
use ReflectionProperty;
use ReflectionException;

class HydrationService
{
    public function __construct() {}

    /**
     * @throws ReflectionException|Exception
     */
    public function hydrate(string $className, array $data): object {
        if (!class_exists($className))
            throw new Exception("Class $className does not exist");
        $reflection = new ReflectionClass($className);

        if (!$reflection->isInstantiable())
            throw new Exception("Class $className is not instantiable");

        $properties = $reflection->getProperties();
        $processedData = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            $nullableAttribute = $this->getPropertyAttribute($property, Nullable::class);
            $defaultValueAttribute = $this->getPropertyAttribute($property, DefaultValue::class);

            $isNullable = $nullableAttribute?->isNullable ?? true;
            $hasDefaultValue = is_null($defaultValueAttribute);

            $processedData[$propertyName] = $this->preparePropertyValue(
                $property,
                $data[$propertyName] ?? null,
                $isNullable,
                $hasDefaultValue,
                $defaultValueAttribute
            );
        }

        return $reflection->newInstanceArgs([$processedData]);
    }

    public function preparePropertyValue(
        ReflectionProperty $property,
        mixed $value,
        bool $isNullable,
        bool $hasDefaultValue,
        ?DefaultValue $defaultValue
    ): mixed {
        if (is_null($value) && !$isNullable && !$hasDefaultValue)
            throw new \InvalidArgumentException(
                "Property '{$property->getName()}' is not nullable and has no default value but NULL was given"
            );

        return $isNullable ? $value : $defaultValue?->value;
    }

    public function getPropertyAttribute(ReflectionProperty $property, string $attributeClass): ?object
    {
        $attributes = $property->getAttributes($attributeClass);
        return $attributes[0]->newInstance() ?? null;
    }
}