<?php

namespace NitsujCodes\PDFDataTable;

/**
 * Css to TCPDF styling methods mappings
 */
class CssMappingRegistry
{
    protected array $mappings;

    public function __construct()
    {
        $this->mappings = [];
    }

    public function register(string $key, callable $callable): void
    {
        $this->mappings[$key] = $callable;
    }

    public function getMapping(string $key): callable
    {
        return $this->mappings[$key];
    }

    public function hasMapping(string $key): bool
    {
        return isset($this->mappings[$key]);
    }

    public function getMappings(): array
    {
        return $this->mappings;
    }
}