<?php

namespace NitsujCodes\PDFDataTable\DTO;

use ReflectionException;

class BaseDTO
{
    private readonly string $objectId;

    public function __construct(){
        $this->objectId = spl_object_hash($this);
    }

    // TODO: Find a way to add a more strict type safety without forcing strict_type=1

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getObjectId(): string
    {
        return $this->objectId;
    }

    /**
     * Update single property and then return a new instance of self
     * @param string $property
     * @param mixed $value
     * @return $this
     * @throws ReflectionException
     */
    public function withProperty(string $property, mixed $value) : static
    {
        return $this->withProperties([$property => $value]);
    }

    /**
     * Update multiple properties and then return a new instance of self
     * @param array $properties
     * @return $this
     * @throws ReflectionException
     */
    public function withProperties(array $properties) : static
    {
        $reflection = new \ReflectionClass(static::class);
        $newArgs = [];
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if (array_key_exists($reflectionProperty->getName(), $properties))
                $newArgs[] = $properties[$reflectionProperty->getName()];
            else
                $newArgs[] = $this->{$reflectionProperty->getName()};
        }
        return $reflection->newInstanceArgs($newArgs);
    }
}