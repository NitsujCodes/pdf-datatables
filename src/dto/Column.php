<?php

namespace NitsujCodes\PDFDataTable\dto;

use NitsujCodes\PDFDataTable\dto\enums\ColumnType;
use NitsujCodes\PDFDataTable\dto\interfaces\IDTO;

class Column extends BaseDTO implements IDTO
{
    public readonly ColumnType $type;
    public readonly string $content;
    public readonly ColumnConfig $config;
}