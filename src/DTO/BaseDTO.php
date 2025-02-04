<?php

namespace NitsujCodes\PDFDataTable\DTO;

use Exception;
use NitsujCodes\PDFDataTable\Services\HydrationService;

class BaseDTO
{
    public function __construct(){}

    // TODO: Find a way to add a more strict type safety without forcing strict_type=1

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): static
    {
        return HydrationService::hydrate(static::class, $data);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}