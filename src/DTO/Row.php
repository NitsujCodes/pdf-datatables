<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Row extends BaseDTO implements IDTO
{
    public readonly array $columns;
    public readonly RowConfig $config;
    public readonly ColumnConfig $defaultColumnConfig;
    public readonly int $columnCount;
}