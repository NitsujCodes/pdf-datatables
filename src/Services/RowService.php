<?php

namespace NitsujCodes\PDFDataTable\Services;

use NitsujCodes\PDFDataTable\DTO\ColumnConfig;
use NitsujCodes\PDFDataTable\DTO\Enums\ColumnType;
use NitsujCodes\PDFDataTable\DTO\Row;
use NitsujCodes\PDFDataTable\DTO\RowConfig;
use NitsujCodes\PDFDataTable\PDFDataTables;

class RowService
{
    public function __construct() {}

    /**
     * @param RowConfig $config
     * @param array $rowColumnsData
     * @param ColumnType|null $columnType
     * @param ColumnConfig|null $defaultColumnConfig
     * @return Row
     */
    public function create(RowConfig $config, array $rowColumnsData, ?ColumnType $columnType = null, ?ColumnConfig $defaultColumnConfig = null): Row
    {
        $columns = [];
        $columnCount = 0;
        foreach ($rowColumnsData as $reference => $columnData) {
            $columns[] = ColumnService::create(
                reference: $reference,
                config: $columnData['config'] ?? $defaultColumnConfig ?? new ColumnConfig(),
                content: $columnData['content'] ?? '',
                columnType: $columnType ?? ColumnType::RowColumn,
            );
            $columnCount++;
        }

        return new Row(
            config: $config,
            defaultColumnConfig: $defaultColumnConfig ?? new ColumnConfig(),
            columnCount: $columnCount,
            columns: $columns,
        );
    }
}