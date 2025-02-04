<?php

namespace NitsujCodes\PDFDataTable\Services;

use ReflectionClass;
use Exception;
use ReflectionParameter;
use ReflectionException;

class HydrationService
{
    public function __construct() {}

    /**
     * @throws ReflectionException|Exception
     */
    public static function hydrate(string $className, array $data): object {
        if (!class_exists($className))
            throw new Exception("Class $className does not exist");
        $reflection = new ReflectionClass($className);

        if (!$reflection->isInstantiable())
            throw new Exception("Class $className is not instantiable");

        $constructorArguments = $reflection->getConstructor()?->getParameters() ?? [];
        $processedData = [];

        foreach ($constructorArguments as $argument) {
            $argName = $argument->getName();

            if (!array_key_exists($argName, $data) && !$argument->isOptional())
                throw new Exception("Missing required parameter '$argName'");

            if (is_null($data[$argName]) && !$argument->allowsNull())
                throw new Exception("Parameter '$argName' is not nullable but NULL was given");

            $processedData[] = !array_key_exists($argName, $data) ?
                $argument->getDefaultValue() : $data[$argName];
        }

        return $reflection->newInstanceArgs($processedData);
    }

    public function getParameterAttribute(ReflectionParameter $parameter, string $attributeClass): ?object
    {
        $attributes = $parameter->getAttributes($attributeClass);
        return $attributes[0]->newInstance() ?? null;
    }
}