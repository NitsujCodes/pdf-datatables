<?php

namespace NitsujCodes\PDFDataTable\Services;

use NitsujCodes\PDFDataTable\DTO\Column;
use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;

class ColumnService
{
    public function __construct()
    {
    }

    /**
     * @param int|string $reference
     * @param string $content
     * @param ColumnType $columnType
     * @return Column
     */
    public static function create(
        int|string $reference, string $content, ColumnType $columnType = ColumnType::RowColumn
    ): Column
    {
        return new Column(
            reference: $reference,
            content: $content,
            type: $columnType,
        );
    }
}