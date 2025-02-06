<?php

namespace NitsujCodes\PDFDataTable\Services;

use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Row;
use NitsujCodes\PDFDataTable\DTO\RowConfig;

class RowService
{
    public function __construct()
    {
    }

    /**
     * Create a Row and then return the reference to it
     * @param ColumnType $columnType
     * @return Row
     */
    public function create(ColumnType $columnType = ColumnType::RowColumn): Row
    {
        return new Row(
            $columnType
        );
    }
}