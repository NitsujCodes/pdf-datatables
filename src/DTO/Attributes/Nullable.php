<?php

namespace NitsujCodes\PDFDataTable\DTO\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Nullable
{
    public function __construct(public readonly bool $isNullable = false) {}
}