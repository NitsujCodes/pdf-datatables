<?php

namespace NitsujCodes\PDFDataTable\dto;

use NitsujCodes\PDFDataTable\dto\interfaces\IDTO;

class Row extends BaseDTO implements IDTO
{
    public readonly array $columns;
    public readonly RowConfig $config;
    public readonly ColumnConfig $defaultColumnConfig;
    public readonly int $columnCount;
}