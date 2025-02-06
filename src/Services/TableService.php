<?php

namespace NitsujCodes\PDFDataTable\Services;

use NitsujCodes\PDFDataTable\DTO\Table;

class TableService
{
    public function __construct(){}

    /**
     * Create a new table DTO
     * @param string $name
     * @param float $maxWidth
     * @param float $maxHeight
     * @param int $maxCellsPerRow
     * @return Table
     */
    public function create(
        string $name, float $maxWidth, float $maxHeight, int $maxCellsPerRow = 1
    ) : Table {
        return new Table(
            name: $name,
            cellsPerRow: $maxCellsPerRow,
            maxWidth: $maxWidth,
            maxHeight: $maxHeight,
        );
    }
}