<?php

namespace NitsujCodes\PDFDataTable\dto;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Nullable
{
    public function __construct(public readonly bool $isNullable = false) {}
}