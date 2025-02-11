<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Column extends BaseDTO implements IDTO
{
    public function __construct(
        public readonly int|string $reference,

        // Optionals
        public readonly bool $isVisible = true,
        public readonly string     $content = '',
        public readonly ColumnType $type = ColumnType::RowColumn,
    )
    {
        parent::__construct();
    }
}