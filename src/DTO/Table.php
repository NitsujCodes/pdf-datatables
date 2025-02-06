<?php

namespace NitsujCodes\PDFDataTable\DTO;

use NitsujCodes\PDFDataTable\DTO\Interfaces\IDTO;

class Table extends BaseDTO implements IDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int    $cellsPerRow,
        public readonly int    $maxWidth,
        public readonly int    $maxHeight,
    )
    {
        parent::__construct();
    }
}