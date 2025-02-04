<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Row extends BaseDTO implements IDTO
{
    public function __construct(
        public readonly array $columns,
        public readonly RowConfig $config,
        public readonly ColumnConfig $defaultColumnConfig,
        public readonly int $columnCount,
    ) {
        parent::__construct();
    }
}