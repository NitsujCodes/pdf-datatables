<?php

namespace NitsujCodes\PDFDataTable\dto;

use NitsujCodes\PDFDataTable\dto\interfaces\IDTO;

class Table extends BaseDTO implements IDTO
{
    public readonly TableConfig $config;
    public readonly int $rowCount;
    public readonly Row $headerRow;
    /** @var Row[] */
    public readonly array $dataRows;
    public readonly RowConfig $defaultRowConfig;
    public readonly ColumnConfig $defaultColumnConfig;
}