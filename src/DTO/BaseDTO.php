<?php

namespace NitsujCodes\PDFDataTable\DTO;

use Exception;
use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;
use NitsujCodes\PDFDataTable\Services\HydrationService;

class BaseDTO
{
    public function __construct(){}

    // TODO: Find a way to add a more strict type safety without forcing strict_type=1

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function updateProperty(string $property, $value) : static
    {
        $reflection = new \ReflectionClass(static::class);

        $newArgs = [];
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getName() == $property)
                $newArgs[] = $value;
            else
                $newArgs[] = $this->{$reflectionProperty->getName()};
        }

        return $reflection->newInstanceArgs($newArgs);
    }
}