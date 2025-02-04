<?php

namespace NitsujCodes\PDFDataTable\DTO\Attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DefaultValue
{
    public function __construct(public mixed $value = null) {}
}