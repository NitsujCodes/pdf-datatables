<?php

namespace NitsujCodes\PDFDataTable\dto\attributes;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DefaultValue
{
    public function __construct(public mixed $value = null) {}
}