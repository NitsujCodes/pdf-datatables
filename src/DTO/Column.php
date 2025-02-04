<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Column extends BaseDTO implements IDTO
{
    public function __construct(
        public readonly ColumnType $type,
        public readonly string $content,
        public readonly ColumnConfig $config,
    ) {
        parent::__construct();
    }
}