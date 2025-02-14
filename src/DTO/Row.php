<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Row extends BaseDTO implements IDTO
{
    public function __construct(
        public readonly ColumnType $defaultColumnType = ColumnType::RowColumn,
    )
    {
        parent::__construct();
    }
}